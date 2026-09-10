@extends('admin.layouts.admin')

@section('title', '页面模板')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.page-templates.create') }}" class="btn bg-primary-500 text-white">新增模板</a>
        </div>
        <strong>页面模板</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>模板名称</th><th width="140">代码</th><th width="90">类别</th><th width="70">默认</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.page-templates.index') }}";

    adminApi.post('/api/admin/pageTemplate/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.templateName ?? r.template_name)}</td>
            <td>${adminApi.esc(r.templateCode ?? r.template_code)}</td>
            <td>${adminApi.esc(r.category)}</td>
            <td>${(r.isDefault ?? r.is_default) ? '是' : '否'}</td>
            <td>
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                ${!(r.isSystem ?? r.is_system) ? `<button class="btn" onclick="destroyRow(${r.id})">删除</button>` : ''}
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该模板？')) return;
        adminApi.post('/api/admin/pageTemplate/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
