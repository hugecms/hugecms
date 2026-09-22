<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\PageTemplateRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class PageTemplateService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly PageTemplateRepository $repository,
    ) {}

    public function getRepository(): PageTemplateRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
