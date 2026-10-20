<?php

declare(strict_types=1);

namespace App\Domains\Form\Services;

use App\Domains\Form\Repositories\FormFieldRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class FormFieldService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly FormFieldRepository $repository,
    ) {}

    public function getRepository(): FormFieldRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
