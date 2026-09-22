<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 前台站点（公开访问，服务端渲染）
|--------------------------------------------------------------------------
*/
Route::get('/', [Site\HomeController::class, 'index'])->name('site.home');
Route::post('/comment', [Site\CommentController::class, 'store'])->name('site.comment');

/*
|--------------------------------------------------------------------------
| 后台认证（免登录）：登录 / 登出 / 找回密码 / 重置密码
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('login', [Admin\AuthController::class, 'login'])->name('auth.login.post');
    Route::post('logout', [Admin\AuthController::class, 'logout'])->name('auth.logout');

    Route::get('password/forgot', [Admin\AuthController::class, 'showForgot'])->name('password.forgot');
    Route::post('password/forgot', [Admin\AuthController::class, 'sendResetLink'])->name('password.forgot.post');
    Route::get('password/reset/{token}', [Admin\AuthController::class, 'showReset'])->name('password.reset');
    Route::post('password/reset', [Admin\AuthController::class, 'resetPassword'])->name('password.reset.post');
});

/*
|--------------------------------------------------------------------------
| 后台页面（仅渲染 Blade；数据经 /api/admin/* 接口获取）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\IndexController::class, 'index'])->name('dashboard');

    // 内容域
    Route::get('contents', [Admin\ContentController::class, 'index'])->name('contents.index');
    Route::get('contents/create', [Admin\ContentController::class, 'create'])->name('contents.create');
    Route::get('contents/{id}/edit', [Admin\ContentController::class, 'edit'])->name('contents.edit');

    Route::get('content-models', [Admin\ContentModelController::class, 'index'])->name('content-models.index');
    Route::get('content-models/create', [Admin\ContentModelController::class, 'create'])->name('content-models.create');
    Route::get('content-models/{id}/edit', [Admin\ContentModelController::class, 'edit'])->name('content-models.edit');

    Route::get('taxonomies', [Admin\TaxonomyController::class, 'index'])->name('taxonomies.index');
    Route::get('taxonomies/create', [Admin\TaxonomyController::class, 'create'])->name('taxonomies.create');
    Route::get('taxonomies/{id}/edit', [Admin\TaxonomyController::class, 'edit'])->name('taxonomies.edit');

    Route::get('comments', [Admin\CommentController::class, 'index'])->name('comments.index');

    Route::get('recycle', [Admin\RecycleBinController::class, 'index'])->name('recycle.index');

    // 媒体
    Route::get('attachments', [Admin\AttachmentController::class, 'index'])->name('attachments.index');

    // 外观
    Route::get('menus', [Admin\MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/create', [Admin\MenuController::class, 'create'])->name('menus.create');
    Route::get('menus/{id}/edit', [Admin\MenuController::class, 'edit'])->name('menus.edit');

    Route::get('page-templates', [Admin\PageTemplateController::class, 'index'])->name('page-templates.index');
    Route::get('page-templates/create', [Admin\PageTemplateController::class, 'create'])->name('page-templates.create');
    Route::get('page-templates/{id}/edit', [Admin\PageTemplateController::class, 'edit'])->name('page-templates.edit');

    Route::get('blocks', [Admin\BlockController::class, 'index'])->name('blocks.index');
    Route::get('blocks/create', [Admin\BlockController::class, 'create'])->name('blocks.create');
    Route::get('blocks/{id}/edit', [Admin\BlockController::class, 'edit'])->name('blocks.edit');

    // 表单
    Route::get('forms', [Admin\FormController::class, 'index'])->name('forms.index');
    Route::get('forms/create', [Admin\FormController::class, 'create'])->name('forms.create');
    Route::get('forms/{id}/edit', [Admin\FormController::class, 'edit'])->name('forms.edit');
    Route::get('forms/{id}/submissions', [Admin\FormController::class, 'submissions'])->name('forms.submissions');

    // 用户权限
    Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::get('users/{id}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');

    Route::get('roles', [Admin\RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [Admin\RoleController::class, 'create'])->name('roles.create');
    Route::get('roles/{id}/edit', [Admin\RoleController::class, 'edit'])->name('roles.edit');

    Route::get('permissions', [Admin\PermissionController::class, 'index'])->name('permissions.index');

    // 推广（插件化保留模块）
    Route::get('ads', [Admin\AdController::class, 'index'])->name('ads.index');
    Route::get('ads/create', [Admin\AdController::class, 'create'])->name('ads.create');
    Route::get('ads/{id}/edit', [Admin\AdController::class, 'edit'])->name('ads.edit');

    Route::get('ad-positions', [Admin\AdPositionController::class, 'index'])->name('ad-positions.index');
    Route::get('ad-positions/create', [Admin\AdPositionController::class, 'create'])->name('ad-positions.create');
    Route::get('ad-positions/{id}/edit', [Admin\AdPositionController::class, 'edit'])->name('ad-positions.edit');

    Route::get('friend-links', [Admin\FriendLinkController::class, 'index'])->name('friend-links.index');
    Route::get('friend-links/create', [Admin\FriendLinkController::class, 'create'])->name('friend-links.create');
    Route::get('friend-links/{id}/edit', [Admin\FriendLinkController::class, 'edit'])->name('friend-links.edit');

    Route::get('short-links', [Admin\ShortLinkController::class, 'index'])->name('short-links.index');
    Route::get('short-links/create', [Admin\ShortLinkController::class, 'create'])->name('short-links.create');
    Route::get('short-links/{id}/edit', [Admin\ShortLinkController::class, 'edit'])->name('short-links.edit');
    Route::get('short-links/{id}/clicks', [Admin\ShortLinkController::class, 'clicks'])->name('short-links.clicks');

    Route::get('push', [Admin\ContentPushController::class, 'index'])->name('push.index');

    // 系统
    Route::get('options', [Admin\OptionController::class, 'index'])->name('options.index');

    Route::get('redirects', [Admin\RedirectController::class, 'index'])->name('redirects.index');
    Route::get('redirects/create', [Admin\RedirectController::class, 'create'])->name('redirects.create');
    Route::get('redirects/{id}/edit', [Admin\RedirectController::class, 'edit'])->name('redirects.edit');

    Route::get('sites', [Admin\SiteController::class, 'index'])->name('sites.index');
    Route::get('sites/{id}/edit', [Admin\SiteController::class, 'edit'])->name('sites.edit');

    Route::get('audit', [Admin\AuditController::class, 'index'])->name('audit.index');

    Route::get('statistics', [Admin\StatisticsController::class, 'index'])->name('statistics.index');
});

/*
|--------------------------------------------------------------------------
| 前台 fallback 路由（必须置于后台之后注册，避免吞掉 /admin）
| 固定链接规则见 options.permalink：内容 /{slug}，分类 /{taxonomy_alias}/{slug}
|--------------------------------------------------------------------------
*/
Route::get('/category/{taxonomyAlias}/{termSlug}', [Site\HomeController::class, 'category'])->name('site.category');
Route::get('/{slug}', [Site\HomeController::class, 'show'])->name('site.show')->where('slug', '[a-z0-9_-]+');
