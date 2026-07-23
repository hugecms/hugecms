<?php

namespace Database\Seeders;

use App\Enums\MenuItemTarget;
use App\Enums\MenuItemType;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $main = Menu::factory()->main()->create();
        Menu::factory()->footer()->create();

        MenuItem::factory()
            ->forMenu($main)
            ->create([
                'title' => '首页',
                'url' => '/',
                'type' => MenuItemType::Custom,
                'target' => MenuItemTarget::Self,
            ]);
    }
}
