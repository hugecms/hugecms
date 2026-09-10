{{-- 管理菜单：数据源为 IndexController::menu()，与权限树模块对应；折叠态由 body.sider-collapsed 控制 --}}
@php
    $menu = \App\Http\Controllers\Admin\IndexController::menu();
@endphp
@foreach ($menu as $group)
    <div class="nav-group">
        <div class="group-title">{{ $group['title'] }}</div>
        <ul>
            @foreach ($group['items'] as $item)
                <li>
                    <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['active']) ? 'active' : '' }}" title="{{ $item['title'] }}">
                        <span class="nav-label">{{ $item['title'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
