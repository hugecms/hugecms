<?php

declare(strict_types=1);

namespace App\Domains\Form\Services;

use App\Domains\Form\Repositories\FormRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class FormService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly FormRepository $repository,
    ) {}

    public function getRepository(): FormRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
