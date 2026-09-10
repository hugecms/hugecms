@extends('admin.layouts.admin')

@section('title', '用户管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.users.create') }}" class="btn bg-primary-500 text-white">新增用户</a>
        </div>
        <strong>用户列表</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>昵称</th><th>邮箱</th><th width="90">状态</th><th width="170">最后登录</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.users.index') }}";

    adminApi.post('/api/admin/user/search', {page: 1, pageSize: 20}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td>${adminApi.esc(r.email)}</td>
            <td>${(r.status ?? 1) ? '启用' : '禁用'}</td>
            <td>${adminApi.esc(r.lastLoginTime ?? r.last_login_time ?? '-')}</td>
            <td>
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该用户？')) return;
        adminApi.post('/api/admin/user/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
