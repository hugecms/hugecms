@extends('admin.layouts.admin')

@section('title', '数据统计')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>每日统计（statistics_daily，Scheduler 聚合，只读）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>统计日期</label>
            <input type="date" class="form-control" id="f-date" style="width:180px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
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
            <tbody id="tbody"><tr><td colspan="8" class="text-gray-500">加载中…</td></tr></tbody>
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
        const date = document.getElementById('f-date').value;
        if (date) f.statDate = date;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/statisticsDaily/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="8" class="text-gray-500">暂无数据（待 Scheduler 生成）</td></tr>';
                return;
            }
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
