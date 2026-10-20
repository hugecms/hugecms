<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Services;

use App\Domains\Shipping\Repositories\ShippingMethodRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShippingMethodService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShippingMethodRepository $repository,
    ) {}

    public function getRepository(): ShippingMethodRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
