<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderCartRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderCartService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderCartRepository $repository,
    ) {}

    public function getRepository(): OrderCartRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
