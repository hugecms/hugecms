<?php

declare(strict_types=1);

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Repositories\PaymentChannelRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentChannelService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PaymentChannelRepository $repository,
    ) {}

    public function getRepository(): PaymentChannelRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
