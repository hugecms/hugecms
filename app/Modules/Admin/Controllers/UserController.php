<?php

declare(strict_types=1);

namespace App\Modules\Admin\Controllers;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Modules\Admin\Requests\UserIndexRequest;
use App\Modules\Admin\Requests\UserStoreRequest;
use App\Modules\Admin\Requests\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    #[OA\Get(path: '/user/create', summary: '新建用户页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function create(): Renderable
    {
        return view('admin::user.create', [
            'statusOptions' => [
                ['value' => UserStatusEnum::Enabled->value, 'label' => UserStatusEnum::Enabled->label()],
                ['value' => UserStatusEnum::Disabled->value, 'label' => UserStatusEnum::Disabled->label()],
            ],
        ]);
    }

    #[OA\Post(path: '/user', summary: '保存新建用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function store(UserStoreRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->string(UserStoreRequest::getName)->toString(),
            'email' => $request->string(UserStoreRequest::getEmail)->toString(),
            'password' => $request->string(UserStoreRequest::getPassword)->toString(),
            'status' => (int) $request->string(UserStoreRequest::getStatus)->toString(),
        ]);

        return redirect()->route('admin.user')->with('status', '创建成功');
    }

    #[OA\Get(path: '/user/edit/{id}', summary: '编辑用户页面', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function edit(int $id): Renderable
    {
        $user = User::findOrFail($id);

        return view('admin::user.edit', [
            'user' => $user,
            'statusOptions' => [
                ['value' => UserStatusEnum::Enabled->value, 'label' => UserStatusEnum::Enabled->label()],
                ['value' => UserStatusEnum::Disabled->value, 'label' => UserStatusEnum::Disabled->label()],
            ],
        ]);
    }

    #[OA\Put(path: '/user/edit/{id}', summary: '更新用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function update(UserUpdateRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $data = [
            'name' => $request->string(UserUpdateRequest::getName)->toString(),
            'email' => $request->string(UserUpdateRequest::getEmail)->toString(),
            'status' => (int) $request->string(UserUpdateRequest::getStatus)->toString(),
        ];
        if ($request->filled(UserUpdateRequest::getPassword)) {
            $data['password'] = $request->string(UserUpdateRequest::getPassword)->toString();
        }
        $user->fill($data)->save();

        return redirect()->route('admin.user')->with('status', '更新成功');
    }

    #[OA\Delete(path: '/user', summary: '删除用户', security: [['bearerAuth' => []]], tags: ['模块'])]
    public function destroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        if ($ids === []) {
            $ids = [$request->integer('id')];
        }
        $ids = array_map(intval(...), array_filter($ids));

        User::destroy($ids);

        return redirect()->route('admin.user')->with('status', '删除成功');
    }
}
