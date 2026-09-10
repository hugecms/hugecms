<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AttachmentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class AttachmentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly AttachmentRepository $repository,
    ) {}

    public function getRepository(): AttachmentRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
