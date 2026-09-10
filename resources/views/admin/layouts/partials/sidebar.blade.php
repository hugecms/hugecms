{{-- 管理菜单：数据源为 IndexController::menu()，与权限树模块对应 --}}
@php
    $menu = \App\Http\Controllers\Admin\IndexController::menu();
    $activeGroup = null;
    foreach ($menu as $group) {
        foreach ($group['items'] as $item) {
            if (request()->routeIs($item['active'])) { $activeGroup = $group['title']; break 2; }
        }
    }
@endphp
@foreach ($menu as $group)
    <div class="nav-group">
        <div class="group-title">{{ $group['title'] }}</div>
        <ul>
            @foreach ($group['items'] as $item)
                <li><a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'active' : '' }}">{{ $item['title'] }}</a></li>
            @endforeach
        </ul>
    </div>
@endforeach
