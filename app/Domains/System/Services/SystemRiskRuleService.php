<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemRiskRuleRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemRiskRuleService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemRiskRuleRepository $repository,
    ) {}

    public function getRepository(): SystemRiskRuleRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
