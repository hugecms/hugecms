@extends('admin.layouts.admin')

@section('title', '点击明细')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.short-links.index') }}" class="btn btn-default">返回短链列表</a>
        </div>
        <strong>短链接点击明细（#{{ $id }}，只增不改）</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th width="140">IP</th><th>UA</th><th>来源页</th><th width="170">点击时间</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="5" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/shortLinkClick/search', {page: 1, pageSize: 30, short_link_id: {{ $id }}}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-gray-500">暂无点击</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.clickIp ?? r.click_ip)}</td>
            <td>${adminApi.esc((r.userAgent ?? r.user_agent ?? '').slice(0, 60))}</td>
            <td>${adminApi.esc((r.referer ?? '').slice(0, 60))}</td>
            <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
        </tr>`).join('');
    });
</script>
@endpush
