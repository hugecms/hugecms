<?php

declare(strict_types=1);

namespace app\domain\system\service;

use app\domain\system\repository\SystemAttachmentRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class SystemAttachmentService extends CommonService implements ServiceInterface
{
    public function getRepository(): SystemAttachmentRepository
    {
        return SystemAttachmentRepository::getInstance();
    }
}
