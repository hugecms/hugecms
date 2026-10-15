<?php

declare(strict_types=1);

namespace app\domain\form\service;

use app\domain\form\repository\FormRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class FormService extends CommonService implements ServiceInterface
{
    public function getRepository(): FormRepository
    {
        return FormRepository::getInstance();
    }
}
