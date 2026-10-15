<?php

declare(strict_types=1);

namespace app\domain\form\service;

use app\domain\form\repository\FormSubmissionRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class FormSubmissionService extends CommonService implements ServiceInterface
{
    public function getRepository(): FormSubmissionRepository
    {
        return FormSubmissionRepository::getInstance();
    }
}
