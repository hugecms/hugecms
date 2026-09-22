@extends('admin.layouts.admin')

@section('title', '管理面板')

@section('content')
{{-- 数据统计卡（Arco Pro 数据卡范式：图标块 + 数值 + 标签） --}}
<div class="flex flex-wrap gap-4">
    <div class="panel stat-card flex-1">
        <div class="panel-body">
            <div class="stat-icon" style="background:var(--primary-1);color:var(--primary-6)">文</div>
            <div>
                <div class="stat-value" id="stat-contents">-</div>
                <div class="stat-label">内容总数</div>
            </div>
        </div>
    </div>
    <div class="panel stat-card flex-1">
        <div class="panel-body">
            <div class="stat-icon" style="background:var(--warning-1);color:#D25F00">评</div>
            <div>
                <div class="stat-value" id="stat-comments">-</div>
                <div class="stat-label">待审评论</div>
            </div>
        </div>
    </div>
    <div class="panel stat-card flex-1">
        <div class="panel-body">
            <div class="stat-icon" style="background:var(--success-1);color:var(--success-6)">人</div>
            <div>
                <div class="stat-value" id="stat-users">-</div>
                <div class="stat-label">用户总数</div>
            </div>
        </div>
    </div>
    <div class="panel stat-card flex-1">
        <div class="panel-body">
            <div class="stat-icon" style="background:var(--danger-1);color:var(--danger-6)">附</div>
            <div>
                <div class="stat-value" id="stat-attachments">-</div>
                <div class="stat-label">附件总数</div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>快捷操作</strong></div>
    <div class="panel-body flex flex-wrap gap-2">
        <a href="{{ route('admin.contents.create') }}" class="btn bg-primary-500 text-white">写内容</a>
        <a href="{{ route('admin.attachments.index') }}" class="btn btn-default">媒体库</a>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-default">菜单管理</a>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-default">评论审核</a>
        <a href="{{ route('admin.options.index') }}" class="btn btn-default">系统设置</a>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="pull-right"><a href="{{ route('admin.contents.index') }}" class="btn btn-link">全部内容</a></div>
        <strong>最近内容</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>标题</th><th width="100">状态</th><th width="170">创建时间</th></tr></thead>
            <tbody id="recent-contents"><tr><td colspan="4" class="text-gray-500">加载中…</td></tr></tbody>
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
        </tr>`).join('') : '<tr><td colspan="4" class="text-gray-500">暂无数据</td></tr>';
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
