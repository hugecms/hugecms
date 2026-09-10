@extends('admin.layouts.admin')

@section('title', '广告管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.ads.create') }}" class="btn btn-primary btn-sm">新增广告</a>
        </div>
        <strong>广告内容</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>广告标题</th><th width="110">类型</th><th width="80">展示</th><th width="80">点击</th><th width="170">投放期</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>广告位</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>广告位名称</th><th width="140">代码</th><th width="110">类型</th><th width="90">最多展示</th></tr></thead>
            <tbody id="positions-tbody"><tr><td colspan="5" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.ads.index') }}";

    adminApi.post('/api/admin/ad/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.title)}</td>
            <td>${adminApi.esc(r.adType ?? r.ad_type)}</td>
            <td>${r.displayCount ?? r.display_count ?? 0}</td>
            <td>${r.clickCount ?? r.click_count ?? 0}</td>
            <td>${adminApi.esc(r.startTime ?? r.start_time ?? '立即')} ~ ${adminApi.esc(r.endTime ?? r.end_time ?? '永久')}</td>
            <td>
                <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    adminApi.post('/api/admin/adPosition/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('positions-tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="5" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td><code>${adminApi.esc(r.code)}</code></td>
            <td>${adminApi.esc(r.adType ?? r.ad_type)}</td>
            <td>${r.maxCount ?? r.max_count ?? 1}</td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该广告？')) return;
        adminApi.post('/api/admin/ad/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
