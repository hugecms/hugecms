<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Entities\MarketingPromotionLadderEntity;
use App\Domains\Marketing\Entities\MarketingPromotionScopeEntity;
use App\Domains\Marketing\Repositories\MarketingPromotionLadderRepository;
use App\Domains\Marketing\Repositories\MarketingPromotionRepository;
use App\Domains\Marketing\Repositories\MarketingPromotionScopeRepository;
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
     * 新增促销活动及范围/档位子表，返回促销活动ID
     */
    public function savePromotion(array $entity, array $scopes, array $ladders): int
    {
        $promotionId = intval($this->insertGetId($entity));

        $this->scopeRepository->saveAll($this->mapScopes($promotionId, $scopes));
        $this->ladderRepository->saveAll($this->mapLadders($promotionId, $ladders));

        return $promotionId;
    }

    /**
     * 更新促销活动并整体替换范围/档位子表
     */
    public function updatePromotion(array $entity, int $id, array $scopes, array $ladders): bool
    {
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
