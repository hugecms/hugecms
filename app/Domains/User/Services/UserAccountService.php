<?php

declare(strict_types=1);

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserAccountLogRepository;
use App\Domains\User\Repositories\UserAccountRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;
use Throwable;

/**
 * 用户资金账户内核：所有余额/冻结变动的唯一入口
 * 守卫全部用条件更新（UPDATE ... WHERE），受影响行数=0 即业务规则失败，跨 MySQL/sqlite 语义一致
 * 每笔变动必落 user_account_logs 双快照（balance_after/frozen_after），SUM(amount) === balance 恒成立可对账
 */
class UserAccountService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserAccountRepository $repository,
        private readonly UserAccountLogRepository $accountLogRepository,
    ) {}

    public function getRepository(): UserAccountRepository
    {
        return $this->repository;
    }

    /**
     * 查询用户账户（无行返回零值结构），available = balance - frozen
     */
    public function getAccount(int $userId): array
    {
        $account = $this->ensureAccount($userId);

        return [
            'balance' => number_format(floatval($account->balance), 2, '.', ''),
            'frozen' => number_format(floatval($account->frozen), 2, '.', ''),
            'available' => number_format(floatval($account->balance) - floatval($account->frozen), 2, '.', ''),
        ];
    }

    /**
     * 入账：余额增加，无下限守卫（佣金入账 biz=1 / 充值入账 biz=9）
     */
    public function credit(int $userId, float $amount, int $bizType, string $bizNo = '', string $description = ''): void
    {
        $this->mutate($userId, $this->normalizeAmount($amount), 0.0, null, $bizType, $bizNo, $description);
    }

    /**
     * 冻结：可用余额转在途（提现冻结 biz=2 / 余额消费冻结 biz=6），守卫 balance - frozen >= amount
     * 守卫金额以内联数字字面量拼接（number_format 仅产出数字/点/负号）：PDO 将 float 绑定为字符串，
     * sqlite 下 REAL 与 TEXT 比较恒为假（类型排序 REAL < TEXT），字面量则跨 MySQL/sqlite 一致
     */
    public function freeze(int $userId, float $amount, int $bizType, string $bizNo = '', string $description = ''): void
    {
        $this->mutate($userId, 0.0, $this->normalizeAmount($amount), fn ($query) => $query->whereRaw('balance - frozen >= '.number_format($amount, 2, '.', '')), $bizType, $bizNo, $description);
    }

    /**
     * 解冻：冻结回可用（提现失败回退 biz=4 / 余额消费退回 biz=8），守卫 frozen >= amount
     */
    public function unfreeze(int $userId, float $amount, int $bizType, string $bizNo = '', string $description = ''): void
    {
        $this->mutate($userId, 0.0, -$this->normalizeAmount($amount), fn ($query) => $query->whereRaw('frozen >= '.number_format($amount, 2, '.', '')), $bizType, $bizNo, $description);
    }

    /**
     * 冻结核销：余额与冻结同减（提现完成 biz=3 / 余额消费完成 biz=7）
     * 双守卫：防提现在途期间佣金被冲销扣成欠款后仍完成打款（此时应走 unfreeze 回退）
     */
    public function settleFrozen(int $userId, float $amount, string $bizNo = '', string $description = ''): void
    {
        $amount = $this->normalizeAmount($amount);
        $literal = number_format($amount, 2, '.', '');
        $this->mutate(
            $userId,
            -$amount,
            -$amount,
            fn ($query) => $query->whereRaw('frozen >= '.$literal)->whereRaw('balance >= '.$literal),
            3,
            $bizNo,
            $description,
        );
    }

    /**
     * 扣回：余额减少可致负（佣金冲销扣回 biz=5），负值即欠款
     */
    public function deduct(int $userId, float $amount, string $bizNo = '', string $description = ''): void
    {
        $this->mutate($userId, -$this->normalizeAmount($amount), 0.0, null, 5, $bizNo, $description);
    }

    /**
     * 人工调整：有符号金额直接调整余额（biz=10），负调整可扣至负
     */
    public function adjust(int $userId, float $amount, string $bizNo = '', string $description = ''): void
    {
        $this->mutate($userId, $this->normalizeAmount($amount), 0.0, null, 10, $bizNo, $description);
    }

    /**
     * 资金流水分页
     */
    public function pageLogs(array $condition = [], int $page = 1, int $perPage = 20): array
    {
        return $this->accountLogRepository->page($condition, $page, $perPage, 'id', 'desc');
    }

    /**
     * 账户行懒创建：并发下唯一键兜底，撞键后重读
     */
    private function ensureAccount(int $userId): object
    {
        $account = $this->repository->builder()->where('user_id', $userId)->first();
        if ($account !== null) {
            return $account;
        }

        try {
            $this->repository->builder()->insert([
                'user_id' => $userId,
                'balance' => 0,
                'frozen' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException) {
            // 并发创建撞 uk_user_accounts_user_id，忽略后重读
        }

        return $this->repository->builder()->where('user_id', $userId)->first();
    }

    /**
     * 唯一写路径：条件更新账户 → 失败抛错 → 回读快照 → 落流水（同一事务）
     *
     * @param  (\Closure(Builder): mixed)|null  $guard  额外守卫条件，null=无条件
     */
    private function mutate(int $userId, float $balanceDelta, float $frozenDelta, ?\Closure $guard, int $bizType, string $bizNo, string $description): void
    {
        // 账户行懒创建：首次变动即开户，后续条件更新才有行可命中
        $this->ensureAccount($userId);

        $balanceDelta = round($balanceDelta, 2);
        $frozenDelta = round($frozenDelta, 2);
        if ($balanceDelta == 0.0 && $frozenDelta == 0.0) {
            throw new BusinessException('变动金额不能为零', 40000);
        }

        try {
            DB::transaction(function () use ($userId, $balanceDelta, $frozenDelta, $guard, $bizType, $bizNo, $description): void {
                $query = $this->repository->builder()->where('user_id', $userId);
                if ($guard !== null) {
                    $guard($query);
                }

                $updates = ['updated_at' => now()->format('Y-m-d H:i:s')];
                if ($balanceDelta != 0.0) {
                    $updates['balance'] = DB::raw('balance + '.number_format($balanceDelta, 2, '.', ''));
                }
                if ($frozenDelta != 0.0) {
                    $updates['frozen'] = DB::raw('frozen + '.number_format($frozenDelta, 2, '.', ''));
                }

                $affected = $query->update($updates);
                if ($affected === 0) {
                    throw new BusinessException($this->guardMessage($bizType), 40001);
                }

                $account = $this->repository->builder()->where('user_id', $userId)->first();

                $this->accountLogRepository->builder()->insert([
                    'user_id' => $userId,
                    'biz_type' => $bizType,
                    'amount' => number_format($balanceDelta, 2, '.', ''),
                    'balance_after' => number_format(floatval($account->balance), 2, '.', ''),
                    'frozen_after' => number_format(floatval($account->frozen), 2, '.', ''),
                    'biz_no' => $bizNo,
                    'description' => $description,
                    'create_time' => now()->format('Y-m-d H:i:s'),
                ]);
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('账户变动失败:'.$e->getMessage(), 40002);
        }
    }

    /**
     * 金额归一：两位小数
     */
    private function normalizeAmount(float $amount): float
    {
        return round($amount, 2);
    }

    /**
     * 守卫失败的用户可读提示
     */
    private function guardMessage(int $bizType): string
    {
        return match ($bizType) {
            2, 6 => '可用余额不足',
            3 => '提现核销失败,请检查在途金额并走失败回退',
            4, 8 => '冻结金额不足',
            default => '账户变动条件不满足',
        };
    }
}
