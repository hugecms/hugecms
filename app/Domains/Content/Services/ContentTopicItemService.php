<?php

declare(strict_types=1);

namespace App\Domains\Content\Services;

use App\Domains\Content\Repositories\ContentTopicItemRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class ContentTopicItemService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly ContentTopicItemRepository $repository,
    ) {}

    public function getRepository(): ContentTopicItemRepository
    {
        return $this->repository;
    }

    // please fill in your code here

}
