@extends('admin.layouts.admin')

@section('title', '权限管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <strong>权限树（种子数据来自 CmsSeeder，9 模块 / 44 操作项）</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th width="80">父级</th><th>名称</th><th width="220">权限代码</th><th width="110">模块</th><th width="70">排序</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/permission/search', {page: 1, pageSize: 200}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => {
            const isModule = !(r.parentId ?? r.parent_id);
            return `<tr ${isModule ? 'style="background:#f6f8fa;font-weight:bold"' : ''}>
                <td>${r.id}</td>
                <td>${r.parentId ?? r.parent_id ?? 0}</td>
                <td>${isModule ? '' : '└ '}${adminApi.esc(r.name)}</td>
                <td><code>${adminApi.esc(r.code)}</code></td>
                <td>${adminApi.esc(r.module)}</td>
                <td>${r.sort ?? 0}</td>
            </tr>`;
        }).join('');
    });
</script>
@endpush
