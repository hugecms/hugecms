<?php

declare(strict_types=1);

namespace App\Domains\Delivery\Services;

use App\Domains\Delivery\Repositories\DeliveryRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class DeliveryService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly DeliveryRepository $repository,
    ) {}

    public function getRepository(): DeliveryRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
