@extends('admin.layouts.admin')

@section('title', '内容管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.contents.create') }}" class="btn btn-primary btn-sm">新增内容</a>
        </div>
        <strong>内容列表</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="60">ID</th>
                <th>标题</th>
                <th width="90">状态</th>
                <th width="80">浏览</th>
                <th width="80">评论</th>
                <th width="170">发布时间</th>
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
    const base = "{{ route('admin.contents.index') }}";

    adminApi.post('/api/admin/content/search', {page: 1, pageSize: 20}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.title)}</td>
            <td><span class="label">${adminApi.esc(r.status)}</span></td>
            <td>${r.views ?? 0}</td>
            <td>${r.commentCount ?? r.comment_count ?? 0}</td>
            <td>${adminApi.esc(r.publishedAt ?? r.published_at ?? '-')}</td>
            <td>
                <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除？删除后将进入回收站。')) return;
        adminApi.post('/api/admin/content/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
