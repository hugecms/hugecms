<?php

declare(strict_types=1);

namespace app\domain\content\service;

use app\domain\content\repository\ContentCommentRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class ContentCommentService extends CommonService implements ServiceInterface
{
    public function getRepository(): ContentCommentRepository
    {
        return ContentCommentRepository::getInstance();
    }
}
