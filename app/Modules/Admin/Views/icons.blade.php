{{--
后台菜单图标集（antd outline 风格 16px 线性 SVG）
用法：@include('admin::icons', ['name' => $menu['icon']])
$name 为空时渲染空槽占位，保证菜单项对齐。
--}}
@php
    $icons = [
        'dashboard' => '<path d="M3 13h8V3H3v10Zm10 8h8V11h-8v10ZM3 21h8v-6H3v6Zm10-12h8V3h-8v6Z"/>',
        'file-text' => '<path d="M9 2h6.5L21 7.5V22H3V2h6Zm0 2H5v16h14V8.4L14.6 4H9Zm2 5h6v2h-6V9Zm0 4h6v2h-6v-2Zm0 4h4v2h-4v-2Z"/>',
        'folder' => '<path d="M10.4 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-8.4l-1.2-2ZM4 6h4.9l1.2 2H20v10H4V6Z"/>',
        'users' => '<path d="M9 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM2 20c0-3.3 3.1-6 7-6s7 2.7 7 6v1H2v-1Zm15.5-4.6c2.6.6 4.5 2.5 4.5 4.6v1h-4.2v-1c0-1.6-.6-3.1-1.6-4.3l1.3-.3Z"/>',
        'shield' => '<path d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3Zm6 9c0 4-2.5 7.9-6 9-3.5-1.1-6-5-6-9V6.3l6-2.2 6 2.2V11Z"/>',
        'menu' => '<path d="M3 4h18v2H3V4Zm0 7h18v2H3v-2Zm0 7h18v2H3v-2Z"/>',
        'setting' => '<path d="M10.6 3h2.8l.4 2.5 2.1 1 2.4-1 1.4 2.4-1.9 1.7v2.4l1.9 1.7-1.4 2.4-2.4-1-2.1 1-.4 2.5h-2.8l-.4-2.5-2.1-1-2.4 1L4.3 15l1.9-1.7v-2.4L4.3 9.2l1.4-2.4 2.4 1 2.1-1 .4-2.5ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>',
        'log' => '<path d="M5 3h14a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm1 2v14h12V5H6Zm2 3h8v2H8V8Zm0 4h8v2H8v-2Zm0 4h5v2H8v-2Z"/>',
    ];
@endphp
@if ($name !== '' && isset($icons[$name]))
    <svg class="admin-menu-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">{{ $icons[$name] }}</svg>
@else
    <span class="admin-menu-icon admin-menu-icon-empty"></span>
@endif
