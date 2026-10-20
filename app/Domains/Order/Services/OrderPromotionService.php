<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderPromotionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderPromotionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderPromotionRepository $repository,
    ) {}

    public function getRepository(): OrderPromotionRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
