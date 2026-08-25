<?php

declare(strict_types=1);

namespace App\Modules\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserIndexRequest extends FormRequest
{
    public const string getEmail = 'email';

    public const string getName = 'name';

    public const string getStatus = 'status';

    public const string getPage = 'page';

    public const string getPageSize = 'pageSize';

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            self::getEmail => ['nullable', 'email'],
            self::getName => ['nullable', 'string', 'max:50'],
            self::getStatus => ['nullable', 'integer', 'in:0,1'],
            self::getPage => ['nullable', 'integer', 'min:1'],
            self::getPageSize => ['nullable', 'integer', 'in:10,20,50'],
        ];
    }
}
