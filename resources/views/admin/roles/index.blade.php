@extends('admin.layouts.admin')

@section('title', '角色管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.roles.create') }}" class="btn bg-primary-500 text-white">新增角色</a>
        </div>
        <strong>角色列表</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>角色名称</th><th width="140">标识</th><th>描述</th><th width="70">内置</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.roles.index') }}";

    adminApi.post('/api/admin/role/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td>${adminApi.esc(r.alias)}</td>
            <td>${adminApi.esc(r.description ?? '')}</td>
            <td>${(r.isSystem ?? r.is_system) ? '是' : '否'}</td>
            <td>
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                ${!(r.isSystem ?? r.is_system) ? `<button class="btn" onclick="destroyRow(${r.id})">删除</button>` : ''}
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该角色？')) return;
        adminApi.post('/api/admin/role/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
