<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\OptionRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class OptionService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly OptionRepository $repository,
    ) {}

    public function getRepository(): OptionRepository
    {
        return $this->repository;
    }

    // please fill in your code here
    
}
