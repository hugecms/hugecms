<?php

declare(strict_types=1);

namespace App\Domains\Marketing\Services;

use App\Domains\Marketing\Repositories\MarketingGroupBuyTeamMemberRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MarketingGroupBuyTeamMemberService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MarketingGroupBuyTeamMemberRepository $repository,
    ) {}

    public function getRepository(): MarketingGroupBuyTeamMemberRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
