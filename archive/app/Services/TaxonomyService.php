<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TaxonomyRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class TaxonomyService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly TaxonomyRepository $repository,
    ) {}

    public function getRepository(): TaxonomyRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
