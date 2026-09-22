<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AttachmentRelationRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AttachmentRelationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AttachmentRelationRepository $repository,
    ) {}

    public function getRepository(): AttachmentRelationRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
