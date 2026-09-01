<?php

declare(strict_types=1);

namespace App\Domains\Promotion\Services;

use App\Domains\Promotion\Repositories\PromotionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PromotionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PromotionRepository $repository,
    ) {}

    public function getRepository(): PromotionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
