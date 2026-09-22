<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TermRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class TermService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly TermRepository $repository,
    ) {}

    public function getRepository(): TermRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
