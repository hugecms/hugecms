@extends('admin.layouts.admin')

@section('title', '管理面板')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-heading"><strong>内容总数</strong></div>
            <div class="panel-body text-center" style="font-size:28px" id="stat-contents">-</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-heading"><strong>待审评论</strong></div>
            <div class="panel-body text-center" style="font-size:28px" id="stat-comments">-</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-heading"><strong>用户总数</strong></div>
            <div class="panel-body text-center" style="font-size:28px" id="stat-users">-</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-heading"><strong>附件总数</strong></div>
            <div class="panel-body text-center" style="font-size:28px" id="stat-attachments">-</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>快捷操作</strong></div>
    <div class="panel-body">
        <a href="{{ route('admin.contents.create') }}" class="btn btn-primary">写内容</a>
        <a href="{{ route('admin.attachments.index') }}" class="btn btn-default">媒体库</a>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-default">菜单管理</a>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-default">评论审核</a>
        <a href="{{ route('admin.options.index') }}" class="btn btn-default">系统设置</a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>最近内容</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>标题</th><th width="100">状态</th><th width="170">创建时间</th></tr></thead>
            <tbody id="recent-contents"><tr><td colspan="4" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/content/search', {page: 1, pageSize: 5}).then(res => {
        const d = res.data ?? {};
        document.getElementById('stat-contents').textContent = d.total ?? '-';
        const tbody = document.getElementById('recent-contents');
        const rows = adminApi.rows(res);
        tbody.innerHTML = rows.length ? rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.title)}</td>
            <td><span class="label">${adminApi.esc(r.status)}</span></td>
            <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
        </tr>`).join('') : '<tr><td colspan="4" class="text-muted">暂无数据</td></tr>';
    });
    adminApi.post('/api/admin/comment/search', {page: 1, pageSize: 1, status: 'pending'}).then(res => {
        document.getElementById('stat-comments').textContent = res.data?.total ?? '-';
    });
    adminApi.post('/api/admin/user/search', {page: 1, pageSize: 1}).then(res => {
        document.getElementById('stat-users').textContent = res.data?.total ?? '-';
    });
    adminApi.post('/api/admin/attachment/search', {page: 1, pageSize: 1}).then(res => {
        document.getElementById('stat-attachments').textContent = res.data?.total ?? '-';
    });
</script>
@endpush
