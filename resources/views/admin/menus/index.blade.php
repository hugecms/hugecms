@extends('admin.layouts.admin')

@section('title', '菜单管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.menus.create') }}" class="btn bg-primary-500 text-white">新增菜单</a>
        </div>
        <strong>菜单集</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>名称</th><th width="140">标识</th><th>描述</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="5" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.menus.index') }}";

    adminApi.post('/api/admin/navMenu/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td>${adminApi.esc(r.alias)}</td>
            <td>${adminApi.esc(r.description ?? '')}</td>
            <td><a class="btn" href="${base}/${r.id}/edit">编辑</a></td>
        </tr>`).join('');
    });
</script>
@endpush
