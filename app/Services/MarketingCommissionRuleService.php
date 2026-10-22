<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\Marketing\Repositories\MarketingDistributionCommissionRuleRepository;
use App\Domains\System\Repositories\SystemSettingRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

/**
 * 佣金规则引擎：命中优先级 商品覆盖 > 分类默认 > system_settings 全局兜底
 */
class MarketingCommissionRuleService extends CommonService implements ServiceInterface
{
    public const int SCOPE_CATEGORY = 1;

    public const int SCOPE_PRODUCT = 2;

    public function __construct(
        private readonly MarketingDistributionCommissionRuleRepository $repository,
        private readonly SystemSettingRepository $settingRepository,
    ) {}

    public function getRepository(): MarketingDistributionCommissionRuleRepository
    {
        return $this->repository;
    }

    /**
     * 解析商品的一级/二级佣金比例，返回 ['level1_rate' => float, 'level2_rate' => float, 'source' => string]
     */
    public function resolveRate(int $productId, int $categoryId): array
    {
        // 商品覆盖
        $rule = $this->repository->builder()
            ->where('status', 1)
            ->where('scope_type', self::SCOPE_PRODUCT)
            ->where('scope_id', $productId)
            ->first();
        if ($rule !== null) {
            return $this->formatRate($rule, 'product');
        }

        // 分类默认
        $rule = $this->repository->builder()
            ->where('status', 1)
            ->where('scope_type', self::SCOPE_CATEGORY)
            ->where('scope_id', $categoryId)
            ->first();
        if ($rule !== null) {
            return $this->formatRate($rule, 'category');
        }

        // 全局兜底
        return [
            'level1_rate' => $this->settingFloat('distribution.level1_rate', 5.00),
            'level2_rate' => $this->settingFloat('distribution.level2_rate', 2.00),
            'source' => 'global',
        ];
    }

    /**
     * 全局比例/保护期读取（缺省回退代码默认值）
     */
    public function settingFloat(string $key, float $default): float
    {
        $value = $this->settingRepository->builder()->where('key', $key)->value('value');

        return $value === null ? $default : floatval($value);
    }

    private function formatRate(object $rule, string $source): array
    {
        return [
            'level1_rate' => floatval($rule->level1_rate),
            'level2_rate' => floatval($rule->level2_rate),
            'source' => $source,
        ];
    }
}
