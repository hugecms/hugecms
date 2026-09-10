@extends('admin.layouts.admin')

@section('title', '内容推送')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>内容分发推送（公众号 / 小程序 / RSS / 站长平台；执行走 Laravel 队列）</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="60">ID</th>
                <th width="70">内容ID</th>
                <th width="120">推送目标</th>
                <th width="90">状态</th>
                <th width="80">重试</th>
                <th>错误信息</th>
                <th width="170">完成时间</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/contentPushQueue/search', {page: 1, pageSize: 20}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无推送记录</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${r.contentId ?? r.content_id}</td>
            <td>${adminApi.esc(r.pushType ?? r.push_type)}</td>
            <td><span class="label">${adminApi.esc(r.status)}</span></td>
            <td>${r.retryCount ?? r.retry_count ?? 0}/${r.maxRetries ?? r.max_retries ?? 3}</td>
            <td>${adminApi.esc((r.errorMessage ?? r.error_message ?? '').slice(0, 50))}</td>
            <td>${adminApi.esc(r.finishedAt ?? r.finished_at ?? '-')}</td>
        </tr>`).join('');
    });
</script>
@endpush
