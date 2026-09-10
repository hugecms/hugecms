@extends('admin.layouts.admin')

@section('title', '友情链接')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.friend-links.create') }}" class="btn bg-primary-500 text-white">新增友链</a>
        </div>
        <strong>友情链接（含审核流）</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>网站名称</th><th>URL</th><th width="100">分类</th><th width="90">状态</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.friend-links.index') }}";
    const statusText = {0: '待审核', 1: '已审核', 2: '已拒绝'};

    adminApi.post('/api/admin/friendLink/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.siteName ?? r.site_name)}</td>
            <td><a href="${adminApi.esc(r.siteUrl ?? r.site_url)}" target="_blank" rel="noopener">${adminApi.esc(r.siteUrl ?? r.site_url)}</a></td>
            <td>${adminApi.esc(r.category)}</td>
            <td>${statusText[r.status] ?? r.status}</td>
            <td>
                ${r.status === 0 ? `<button class="btn" onclick="approve(${r.id})">通过</button>` : ''}
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function approve(id) {
        adminApi.put('/api/admin/friendLink/update', {id, status: 1}).then(() => location.reload());
    }

    function destroyRow(id) {
        if (!confirm('确认删除该友链？')) return;
        adminApi.post('/api/admin/friendLink/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
