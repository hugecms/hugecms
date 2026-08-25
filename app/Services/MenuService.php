<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Menu;
use App\Repositories\MenuRepository;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MenuService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MenuRepository $repository,
    ) {}

    public function getRepository(): MenuRepository
    {
        return $this->repository;
    }

    /**
     * 后台侧边栏菜单树（仅可见项，按 sort 升序）
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSidebarMenus(): array
    {
        $menus = Menu::query()
            ->where('visible', true)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        $groups = [];
        $children = [];
        foreach ($menus as $menu) {
            if ($menu->parent_id === null) {
                $groups[$menu->id] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'route' => $menu->route,
                    'children' => [],
                ];
            } else {
                $children[$menu->parent_id][] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'icon' => $menu->icon,
                    'route' => $menu->route,
                ];
            }
        }

        foreach ($children as $parentId => $items) {
            if (isset($groups[$parentId])) {
                $groups[$parentId]['children'] = $items;
            }
        }

        return array_values($groups);
    }
}
