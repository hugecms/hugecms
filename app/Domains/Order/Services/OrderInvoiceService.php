<?php

declare(strict_types=1);

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderInvoiceRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OrderInvoiceService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OrderInvoiceRepository $repository,
    ) {}

    public function getRepository(): OrderInvoiceRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
