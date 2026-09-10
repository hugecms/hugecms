@extends('admin.layouts.admin')

@section('title', '站点管理')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>站点列表（默认单站点模式仅一条记录；多站点启用见 init.sql 附注）</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>站点名称</th><th>主域名</th><th width="110">默认语言</th><th width="90">状态</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.sites.index') }}";

    adminApi.post('/api/admin/site/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.siteName ?? r.site_name)}</td>
            <td>${adminApi.esc(r.domain)}</td>
            <td>${adminApi.esc(r.language)}</td>
            <td>${(r.status ?? 1) ? '启用' : '停用'}</td>
            <td><a class="btn" href="${base}/${r.id}/edit">编辑</a></td>
        </tr>`).join('');
    });
</script>
@endpush
