<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FormTemplateRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class FormTemplateService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly FormTemplateRepository $repository,
    ) {}

    public function getRepository(): FormTemplateRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
