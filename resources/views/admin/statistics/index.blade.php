@extends('admin.layouts.admin')

@section('title', '数据统计')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>每日统计（statistics_daily，Scheduler 聚合，只读）</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="110">日期</th>
                <th width="90">新增内容</th>
                <th width="90">发布内容</th>
                <th width="90">总内容</th>
                <th width="100">浏览量</th>
                <th width="90">新评论</th>
                <th width="90">新用户</th>
                <th width="90">活跃用户</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="8" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/statisticsDaily/search', {page: 1, pageSize: 30}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-muted">暂无数据（待 Scheduler 生成）</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${adminApi.esc(r.statDate ?? r.stat_date)}</td>
            <td>${r.newContents ?? r.new_contents ?? 0}</td>
            <td>${r.publishedContents ?? r.published_contents ?? 0}</td>
            <td>${r.totalContents ?? r.total_contents ?? 0}</td>
            <td>${r.totalViews ?? r.total_views ?? 0}</td>
            <td>${r.newComments ?? r.new_comments ?? 0}</td>
            <td>${r.newUsers ?? r.new_users ?? 0}</td>
            <td>${r.activeUsers ?? r.active_users ?? 0}</td>
        </tr>`).join('');
    });
</script>
@endpush
