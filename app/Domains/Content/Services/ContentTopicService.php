<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentTopicRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentTopicService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentTopicRepository $repository,
    ) {}

    public function getRepository(): ContentTopicRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
