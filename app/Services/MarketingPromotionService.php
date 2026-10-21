<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Marketing\Entities\MarketingPromotionLadderEntity;
use App\Domains\Marketing\Entities\MarketingPromotionScopeEntity;
use App\Domains\Marketing\Repositories\MarketingPromotionLadderRepository;
use App\Domains\Marketing\Repositories\MarketingPromotionRepository;
use App\Domains\Marketing\Repositories\MarketingPromotionScopeRepository;
use Illuminate\Validation\ValidationException;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingPromotionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingPromotionRepository $repository,
        private readonly MarketingPromotionScopeRepository $scopeRepository,
        private readonly MarketingPromotionLadderRepository $ladderRepository,
    ) {}

    public function getRepository(): MarketingPromotionRepository
    {
        return $this->repository;
    }

    /**
     * 新增促销活动及范围/档位子表，返回促销活动ID（含业务联动校验）
     */
    public function savePromotion(array $entity, array $scopes, array $ladders): int
    {
        $this->validate($entity, $scopes, $ladders);

        $promotionId = intval($this->insertGetId($entity));

        $this->scopeRepository->saveAll($this->mapScopes($promotionId, $scopes));
        $this->ladderRepository->saveAll($this->mapLadders($promotionId, $ladders));

        return $promotionId;
    }

    /**
     * 更新促销活动并整体替换范围/档位子表（含业务联动校验）
     */
    public function updatePromotion(array $entity, int $id, array $scopes, array $ladders): bool
    {
        $this->validate($entity, $scopes, $ladders);

        // 父表数据未变化时 updateById 返回 false，不能作为成功判据，以子表整体替换为准
        $this->updateById($entity, $id);

        $this->scopeRepository->delete(['promotion_id' => $id]);
        $this->ladderRepository->delete(['promotion_id' => $id]);

        $this->scopeRepository->saveAll($this->mapScopes($id, $scopes));
        $this->ladderRepository->saveAll($this->mapLadders($id, $ladders));

        return true;
    }

    /**
     * 查询促销活动详情，含范围/档位子表
     */
    public function getDetailById(int $id): array
    {
        $promotion = $this->getOneById($id);
        if (empty($promotion)) {
            return [];
        }

        $promotion['scopes'] = array_map(
            fn (array $row): array => MarketingPromotionScopeEntity::from($row)->toArray(),
            $this->scopeRepository->findAll(['promotion_id' => $id]),
        );
        $promotion['ladders'] = array_map(
            fn (array $row): array => MarketingPromotionLadderEntity::from(
                $this->stringifyDecimals($row, ['min_amount', 'reduce_amount', 'discount_rate']),
            )->toArray(),
            $this->ladderRepository->findAll(['promotion_id' => $id]),
        );

        return $promotion;
    }

    /**
     * sqlite 测试环境 decimal 列返回 int/float，统一转为字符串以匹配实体 string 类型
     */
    private function stringifyDecimals(array $row, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($row[$field]) && ! is_string($row[$field])) {
                $row[$field] = (string) $row[$field];
            }
        }

        return $row;
    }

    /**
     * 批量删除促销活动，级联清理范围/档位子表
     */
    public function removeByIds(array $ids): bool
    {
        $this->scopeRepository->delete(['promotion_id' => $ids]);
        $this->ladderRepository->delete(['promotion_id' => $ids]);

        return parent::removeByIds($ids);
    }

    /**
     * 业务联动校验：类型与档位优惠参数互斥、适用范围与范围列表一致、范围目标去重、门槛金额不重复
     * 以 ValidationException 抛出（字段路径键），HTTP 层渲染 422 + errors，前端可直接定位字段
     */
    private function validate(array $entity, array $scopes, array $ladders): void
    {
        $type = $entity['type'] ?? '';
        $rangeType = $entity['range_type'] ?? '';
        $errors = [];

        if (! in_array($type, ['full_reduce', 'discount'], true)) {
            $errors['type'] = ['类型错误,仅支持full_reduce=满减,discount=折扣'];
        }
        if (! in_array($rangeType, ['all', 'category', 'brand', 'product'], true)) {
            $errors['rangeType'] = ['适用范围错误,仅支持all=全场,category=指定分类,brand=指定品牌,product=指定商品'];
        }
        if ($ladders === []) {
            $errors['ladders'] = ['请设置优惠档位'];
        }

        $minAmounts = [];
        foreach ($ladders as $index => $ladder) {
            if (! is_array($ladder)) {
                continue;
            }

            $minAmount = $ladder['minAmount'] ?? null;
            $reduceAmount = $ladder['reduceAmount'] ?? null;
            $discountRate = $ladder['discountRate'] ?? null;

            if ($minAmount === null || $minAmount === '') {
                $errors["ladders.{$index}.minAmount"] = ['档位门槛金额必填'];
            } elseif (in_array((string) $minAmount, $minAmounts, true)) {
                $errors["ladders.{$index}.minAmount"] = ['档位门槛金额不能重复'];
            } else {
                $minAmounts[] = (string) $minAmount;
            }

            if ($type === 'full_reduce') {
                if (in_array($reduceAmount, [null, ''], true)) {
                    $errors["ladders.{$index}.reduceAmount"] = ['满减活动每档必须设置满减金额'];
                }
                if ($discountRate !== null && $discountRate !== '') {
                    $errors["ladders.{$index}.discountRate"] = ['满减活动档位不能设置折扣率'];
                }
            }

            if ($type === 'discount') {
                if (in_array($discountRate, [null, ''], true)) {
                    $errors["ladders.{$index}.discountRate"] = ['折扣活动每档必须设置折扣率'];
                }
                if ($reduceAmount !== null && $reduceAmount !== '') {
                    $errors["ladders.{$index}.reduceAmount"] = ['折扣活动档位不能设置满减金额'];
                }
            }
        }

        $combinations = [];
        if ($rangeType === 'all') {
            if ($scopes !== []) {
                $errors['scopes'] = ['全场活动不能设置适用范围'];
            }
        } elseif ($scopes === []) {
            $errors['scopes'] = ['请设置适用范围'];
        } else {
            foreach ($scopes as $index => $scope) {
                if (! is_array($scope)) {
                    continue;
                }

                $scopeType = $scope['scopeType'] ?? '';
                if ($scopeType !== $rangeType) {
                    $errors["scopes.{$index}.scopeType"] = ['范围类型必须与适用范围一致'];
                }

                $combination = $scopeType.':'.($scope['targetId'] ?? '');
                if (in_array($combination, $combinations, true)) {
                    $errors["scopes.{$index}.targetId"] = ['适用范围目标重复'];
                } else {
                    $combinations[] = $combination;
                }
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * 范围载荷(camelCase)映射为子表行(snake_case)
     */
    private function mapScopes(int $promotionId, array $scopes): array
    {
        $now = now()->format('Y-m-d H:i:s');

        return array_map(fn (array $scope): array => [
            'promotion_id' => $promotionId,
            'scope_type' => $scope['scopeType'],
            'target_id' => intval($scope['targetId']),
            'created_at' => $now,
            'updated_at' => $now,
        ], $scopes);
    }

    /**
     * 档位载荷(camelCase)映射为子表行(snake_case)
     */
    private function mapLadders(int $promotionId, array $ladders): array
    {
        $now = now()->format('Y-m-d H:i:s');

        return array_map(fn (array $ladder): array => [
            'promotion_id' => $promotionId,
            'min_amount' => $ladder['minAmount'],
            'reduce_amount' => $ladder['reduceAmount'] ?? null,
            'discount_rate' => $ladder['discountRate'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ], $ladders);
    }
}
