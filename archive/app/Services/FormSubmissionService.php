<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\FormSubmissionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class FormSubmissionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly FormSubmissionRepository $repository,
    ) {}

    public function getRepository(): FormSubmissionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
