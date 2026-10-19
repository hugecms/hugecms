<?php

declare(strict_types=1);

namespace App\Domains\Form\Services;

use App\Domains\Form\Repositories\FormSubmissionRepository;
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
