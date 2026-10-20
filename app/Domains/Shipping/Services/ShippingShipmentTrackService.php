<?php

declare(strict_types=1);

namespace App\Domains\Shipping\Services;

use App\Domains\Shipping\Repositories\ShippingShipmentTrackRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ShippingShipmentTrackService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ShippingShipmentTrackRepository $repository,
    ) {}

    public function getRepository(): ShippingShipmentTrackRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
