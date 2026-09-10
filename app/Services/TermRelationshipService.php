<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TermRelationshipRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class TermRelationshipService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly TermRelationshipRepository $repository,
    ) {}

    public function getRepository(): TermRelationshipRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
