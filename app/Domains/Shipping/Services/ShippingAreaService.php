<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Services;

use App\Domains\Shipping\Repositories\ShippingAreaRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShippingAreaService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShippingAreaRepository $repository,
    ) {}

    public function getRepository(): ShippingAreaRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
