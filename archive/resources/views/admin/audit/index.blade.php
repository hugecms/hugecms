@extends('admin.layouts.admin')

@section('title', '审计日志')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>操作审计（只读，写多读少，建议按月归档）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="目标名称" style="width:200px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
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
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/auditLog/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.userName ?? r.user_name ?? (r.userId ?? r.user_id))}</td>
                <td><span class="label">${adminApi.esc(r.eventType ?? r.event_type)}</span></td>
                <td>${adminApi.esc(r.targetType ?? r.target_type)} #${adminApi.esc(r.targetId ?? r.target_id)}</td>
                <td>${adminApi.esc(r.targetName ?? r.target_name ?? '')}</td>
                <td>${(r.operationResult ?? r.operation_result) ? '成功' : '失败'}</td>
                <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
            </tr>`).join('');
            adminApi.pager('pager', res, load);
        });
    }

    document.getElementById('filter-form').addEventListener('submit', e => { e.preventDefault(); load(); });
    document.getElementById('f-reset').addEventListener('click', () => {
        document.querySelectorAll('#filter-form input, #filter-form select').forEach(el => el.value = '');
        load();
    });

    load();
</script>
@endpush
