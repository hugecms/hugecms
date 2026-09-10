@extends('admin.layouts.admin')

@section('title', '内容模型')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.content-models.create') }}" class="btn btn-primary btn-sm">新增模型</a>
        </div>
        <strong>内容模型</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="60">ID</th>
                <th>名称</th>
                <th width="120">别名</th>
                <th width="140">数据表</th>
                <th width="70">内置</th>
                <th width="70">可评论</th>
                <th width="130">操作</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.content-models.index') }}";

    adminApi.post('/api/admin/contentModel/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td>${adminApi.esc(r.alias)}</td>
            <td>${adminApi.esc(r.tableName ?? r.table_name)}</td>
            <td>${(r.isSystem ?? r.is_system) ? '是' : '否'}</td>
            <td>${(r.isCommentable ?? r.is_commentable) ? '是' : '否'}</td>
            <td>
                <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                ${!(r.isSystem ?? r.is_system) ? `<button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>` : ''}
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该模型？其数据表与内容将一并删除。')) return;
        adminApi.post('/api/admin/contentModel/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
