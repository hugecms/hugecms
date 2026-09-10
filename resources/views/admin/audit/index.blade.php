@extends('admin.layouts.admin')

@section('title', '审计日志')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>操作审计（只读，写多读少，建议按月归档）</strong></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="60">ID</th>
                <th width="110">操作人</th>
                <th width="110">事件</th>
                <th width="140">目标</th>
                <th>目标名称</th>
                <th width="60">结果</th>
                <th width="170">时间</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    adminApi.post('/api/admin/auditLog/search', {page: 1, pageSize: 20}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.userName ?? r.user_name ?? (r.userId ?? r.user_id))}</td>
            <td><span class="label">${adminApi.esc(r.eventType ?? r.event_type)}</span></td>
            <td>${adminApi.esc(r.targetType ?? r.target_type)} #${adminApi.esc(r.targetId ?? r.target_id)}</td>
            <td>${adminApi.esc(r.targetName ?? r.target_name ?? '')}</td>
            <td>${(r.operationResult ?? r.operation_result) ? '成功' : '失败'}</td>
            <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
        </tr>`).join('');
    });
</script>
@endpush
