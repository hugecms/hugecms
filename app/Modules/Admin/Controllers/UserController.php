<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Enums\UserStatusEnum;
use App\Modules\Admin\Requests\UserIndexRequest;
use App\Services\UserService;
use Illuminate\Contracts\Support\Renderable;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    public function __construct(
        private readonly UserService $userService,
    ) {}

    #[OA\Get(path: '/user', summary: '用户列表页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function index(UserIndexRequest $request): Renderable
    {
        $filters = array_filter($request->only([
            UserIndexRequest::getEmail,
            UserIndexRequest::getName,
            UserIndexRequest::getStatus,
        ]), fn ($value) => $value !== null && $value !== '');

        $condition = [];
        if (isset($filters[UserIndexRequest::getEmail])) {
            $condition[] = ['email', '=', $filters[UserIndexRequest::getEmail]];
        }
        if (isset($filters[UserIndexRequest::getName])) {
            $condition[] = ['name', 'like', '%'.$filters[UserIndexRequest::getName].'%'];
        }
        if (isset($filters[UserIndexRequest::getStatus])) {
            $condition[] = ['status', '=', (int) $filters[UserIndexRequest::getStatus]];
        }

        $page = (int) $request->query(UserIndexRequest::getPage, '1');
        $pageSize = (int) $request->query(UserIndexRequest::getPageSize, '10');

        $users = $this->userService->page($condition, $page, $pageSize);

        return view('admin::user.index', [
            'users' => $users,
            'filters' => $filters,
            'statusOptions' => [
                ['value' => UserStatusEnum::Enabled->value, 'label' => UserStatusEnum::Enabled->label()],
                ['value' => UserStatusEnum::Disabled->value, 'label' => UserStatusEnum::Disabled->label()],
            ],
            'pageSize' => $pageSize,
        ]);
    }
}
