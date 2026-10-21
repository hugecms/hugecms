<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\System\Repositories\SystemSettingRepository;
use App\Domains\User\Enums\UserAccountLogBizTypeEnum;
use App\Domains\User\Enums\UserWithdrawOrderStatusEnum;
use App\Domains\User\Repositories\UserWithdrawOrderRepository;
use App\Plugins\Pay\PayService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Exceptions\BusinessException;
use Juling\Foundation\Services\CommonService;
use Throwable;

/**
 * 提现单状态机：10待审核→20审核通过→30打款中→40成功；10→50审核拒绝；30→60打款失败已回退
 * 全部迁移用条件更新（where status=旧值）守卫，非法迁移受影响行数=0 抛错；
 * 冻结/回退/核销走 UserAccountService（frozen 在途），打款本期人工线下，渠道集成后走 PayService
 */
class UserWithdrawOrderService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly UserWithdrawOrderRepository $repository,
        private readonly UserAccountService $accountService,
        private readonly RiskService $riskService,
        private readonly SystemSettingRepository $settingRepository,
        private readonly PayService $payService,
    ) {}

    public function getRepository(): UserWithdrawOrderRepository
    {
        return $this->repository;
    }

    /**
     * 申请提现：校验金额/频次 → 风控前置检查 → 冻结可用余额 → 建待审核单（同一事务）
     */
    public function apply(int $userId, array $input): int
    {
        $amount = round(floatval($input['amount'] ?? 0), 2);
        $minAmount = $this->settingFloat('withdraw.min_amount', 10.00);
        if ($amount < $minAmount) {
            throw new BusinessException(sprintf('提现金额不能低于%.2f元', $minAmount), 40020);
        }

        $dailyLimit = (int) $this->settingFloat('withdraw.daily_limit', 1);
        $inFlight = $this->repository->builder()
            ->where('user_id', $userId)
            ->whereIn('status', [10, 20, 30])
            ->where('created_at', '>=', now()->startOfDay())
            ->count();
        if ($inFlight >= $dailyLimit) {
            throw new BusinessException('今日提现申请次数已达上限', 40021);
        }

        $withdrawNo = $this->generateWithdrawNo();

        // 风控前置：拦截型规则抛错，标记型放行留痕
        $this->riskService->checkWithdraw($userId, $amount, $withdrawNo);

        try {
            return (int) DB::transaction(function () use ($userId, $input, $amount, $withdrawNo): int {
                $this->accountService->freeze($userId, $amount, UserAccountLogBizTypeEnum::BizType2->value, $withdrawNo, '提现申请冻结');

                return (int) $this->repository->builder()->insertGetId([
                    'withdraw_no' => $withdrawNo,
                    'user_id' => $userId,
                    'amount' => number_format($amount, 2, '.', ''),
                    'channel' => 'manual',
                    'account_type' => (string) ($input['accountType'] ?? ''),
                    'account_name' => (string) ($input['accountName'] ?? ''),
                    'account_no' => (string) ($input['accountNo'] ?? ''),
                    'status' => UserWithdrawOrderStatusEnum::Status10->value,
                    'user_note' => (string) ($input['userNote'] ?? ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } catch (BusinessException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new BusinessException('提现申请失败:'.$e->getMessage(), 40022);
        }
    }

    /**
     * 审核：通过 10→20（不动资金）；拒绝 10→50 并解冻回可用；重复审核幂等
     */
    public function audit(int $id, bool $pass, string $adminNote = ''): void
    {
        $order = $this->getOrder($id);

        DB::transaction(function () use ($order, $pass, $adminNote): void {
            $target = $pass ? UserWithdrawOrderStatusEnum::Status20 : UserWithdrawOrderStatusEnum::Status50;
            $affected = $this->transition($order->id, UserWithdrawOrderStatusEnum::Status10, [
                'status' => $target->value,
                'admin_note' => $adminNote,
                'audit_at' => now(),
            ]);

            if ($affected === 0) {
                // 幂等：已是目标终态直接返回，绝不二次回退
                $current = $this->getOrder((int) $order->id);
                if ((int) $current->status === $target->value) {
                    return;
                }

                throw new BusinessException('当前状态不可审核', 40023);
            }

            if (! $pass) {
                $this->accountService->unfreeze(
                    (int) $order->user_id,
                    floatval($order->amount),
                    UserAccountLogBizTypeEnum::BizType4->value,
                    (string) $order->withdraw_no,
                    '提现审核拒绝回退',
                );
            }
        });
    }

    /**
     * 标记打款中：20→30，发起渠道打款（本期线下返回空渠道单号，人工回填）
     */
    public function markPaying(int $id): void
    {
        $order = $this->getOrder($id);

        $affected = $this->transition($id, UserWithdrawOrderStatusEnum::Status20, [
            'status' => UserWithdrawOrderStatusEnum::Status30->value,
        ]);
        if ($affected === 0) {
            throw new BusinessException('当前状态不可发起打款', 40024);
        }

        $this->payService->withdraw([
            'withdraw_no' => (string) $order->withdraw_no,
            'amount' => floatval($order->amount),
            'account_type' => (string) $order->account_type,
            'account_name' => (string) $order->account_name,
            'account_no' => (string) $order->account_no,
        ]);
    }

    /**
     * 标记打款成功：30→40 并冻结核销（余额冻结同减）；同渠道单号重放幂等，异号撞唯一键报错
     */
    public function markSuccess(int $id, string $channelOrderNo): void
    {
        $order = $this->getOrder($id);

        try {
            DB::transaction(function () use ($order, $channelOrderNo): void {
                $affected = $this->transition((int) $order->id, UserWithdrawOrderStatusEnum::Status30, [
                    'status' => UserWithdrawOrderStatusEnum::Status40->value,
                    'channel_order_no' => $channelOrderNo,
                    'paid_at' => now(),
                ]);

                if ($affected === 0) {
                    // 幂等重放：同单同渠道单号已成功则直接返回
                    $current = $this->getOrder((int) $order->id);
                    if ((int) $current->status === UserWithdrawOrderStatusEnum::Status40->value
                        && (string) $current->channel_order_no === $channelOrderNo) {
                        return;
                    }

                    throw new BusinessException('当前状态不可标记成功', 40025);
                }

                $this->accountService->settleFrozen(
                    (int) $order->user_id,
                    floatval($order->amount),
                    (string) $order->withdraw_no,
                    '提现打款成功核销',
                );
            });
        } catch (QueryException $e) {
            // uk(channel, channel_order_no) 撞键：同号同单视为已成功，异单占用则拒绝
            $occupied = $this->repository->builder()
                ->where('channel', $order->channel)
                ->where('channel_order_no', $channelOrderNo)
                ->first();
            if ($occupied !== null && (int) $occupied->id === (int) $order->id) {
                return;
            }

            throw new BusinessException('渠道打款单号已被其他提现单使用', 40026);
        }
    }

    /**
     * 标记打款失败：30→60 并解冻回可用；重复标记幂等
     */
    public function markFail(int $id, string $reason): void
    {
        $order = $this->getOrder($id);

        DB::transaction(function () use ($order, $reason): void {
            $affected = $this->transition((int) $order->id, UserWithdrawOrderStatusEnum::Status30, [
                'status' => UserWithdrawOrderStatusEnum::Status60->value,
                'fail_reason' => $reason,
                'paid_at' => now(),
            ]);

            if ($affected === 0) {
                $current = $this->getOrder((int) $order->id);
                if ((int) $current->status === UserWithdrawOrderStatusEnum::Status60->value) {
                    return;
                }

                throw new BusinessException('当前状态不可标记失败', 40027);
            }

            $this->accountService->unfreeze(
                (int) $order->user_id,
                floatval($order->amount),
                UserAccountLogBizTypeEnum::BizType4->value,
                (string) $order->withdraw_no,
                '提现打款失败回退',
            );
        });
    }

    /**
     * C 端：我的提现单分页
     */
    public function listMine(int $userId, int $page = 1, int $perPage = 20): array
    {
        return $this->repository->page([['user_id', '=', $userId]], $page, $perPage, 'id', 'desc');
    }

    /**
     * 条件状态迁移封装：where id + where status=from
     */
    private function transition(int $id, UserWithdrawOrderStatusEnum $from, array $data): int
    {
        $data['updated_at'] = now();

        return $this->repository->builder()
            ->where('id', $id)
            ->where('status', $from->value)
            ->update($data);
    }

    private function getOrder(int $id): object
    {
        $order = $this->repository->builder()->where('id', $id)->first();
        if ($order === null) {
            throw new BusinessException('提现单不存在', 40028);
        }

        return $order;
    }

    /**
     * 单号：W + 时间戳 + 随机后缀，撞唯一键重试一次
     */
    private function generateWithdrawNo(): string
    {
        for ($i = 0; $i < 2; $i++) {
            $no = 'W'.date('YmdHis').strtoupper(substr(uniqid(), -6));
            if (! $this->repository->builder()->where('withdraw_no', $no)->exists()) {
                return $no;
            }
        }

        throw new BusinessException('提现单号生成失败,请重试', 40029);
    }

    private function settingFloat(string $key, float $default): float
    {
        $value = $this->settingRepository->builder()->where('key', $key)->value('value');

        return $value === null ? $default : floatval($value);
    }
}
