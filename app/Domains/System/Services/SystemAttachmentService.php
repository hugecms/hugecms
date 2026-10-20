<?php

declare(strict_types=1);

namespace App\Domains\System\Services;

use App\Domains\System\Repositories\SystemAttachmentRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class SystemAttachmentService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly SystemAttachmentRepository $repository,
    ) {}

    public function getRepository(): SystemAttachmentRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
