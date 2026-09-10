@extends('admin.layouts.admin')

@section('title', '表单管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.forms.create') }}" class="btn btn-primary btn-sm">新增表单</a>
        </div>
        <strong>表单模板</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>表单名称</th><th width="140">标识</th><th width="100">提交数</th><th width="80">状态</th><th width="170">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.forms.index') }}";

    adminApi.post('/api/admin/formTemplate/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td>${adminApi.esc(r.alias)}</td>
            <td>${r.submitCount ?? r.submit_count ?? 0}</td>
            <td>${(r.isActive ?? r.is_active) ? '启用' : '停用'}</td>
            <td>
                <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                <a class="btn btn-sm" href="${base}/${r.id}/submissions">提交数据</a>
                <button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该表单？其提交数据将一并删除。')) return;
        adminApi.post('/api/admin/formTemplate/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
