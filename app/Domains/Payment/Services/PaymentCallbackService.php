<?php

declare(strict_types=1);

namespace App\Domains\Payment\Services;

use App\Domains\Payment\Repositories\PaymentCallbackRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PaymentCallbackService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PaymentCallbackRepository $repository,
    ) {}

    public function getRepository(): PaymentCallbackRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
