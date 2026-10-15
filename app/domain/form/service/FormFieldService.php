<?php

declare(strict_types=1);

namespace app\domain\form\service;

use app\domain\form\repository\FormFieldRepository;
use Juling\Foundation\Contract\ServiceInterface;
use Juling\Foundation\Service\CommonService;

class FormFieldService extends CommonService implements ServiceInterface
{
    public function getRepository(): FormFieldRepository
    {
        return FormFieldRepository::getInstance();
    }
}
