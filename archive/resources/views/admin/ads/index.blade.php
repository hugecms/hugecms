@extends('admin.layouts.admin')

@section('title', '广告管理')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.ads.create') }}" class="btn bg-primary-500 text-white">新增广告</a>
        </div>
        <strong>广告内容</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="广告标题" style="width:200px">
        </div>
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" id="f-status" style="width:140px">
                <option value="">全部</option>
                <option value="1">启用</option>
                <option value="0">停用</option>
            </select>
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>广告标题</th><th width="110">类型</th><th width="80">展示</th><th width="80">点击</th><th width="170">投放期</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>

<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading"><strong>广告位</strong></div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="position-filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="position-keyword" placeholder="广告位名称" style="width:200px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="position-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>广告位名称</th><th width="140">代码</th><th width="110">类型</th><th width="90">最多展示</th></tr></thead>
            <tbody id="positions-tbody"><tr><td colspan="5" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="positions-pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.ads.index') }}";
    let currentPage = 1;
    let positionPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        const status = document.getElementById('f-status').value;
        if (status !== '') f.status = status;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/ad/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.title)}</td>
                <td>${adminApi.esc(r.adType ?? r.ad_type)}</td>
                <td>${r.displayCount ?? r.display_count ?? 0}</td>
                <td>${r.clickCount ?? r.click_count ?? 0}</td>
                <td>${adminApi.esc(r.startTime ?? r.start_time ?? '立即')} ~ ${adminApi.esc(r.endTime ?? r.end_time ?? '永久')}</td>
                <td>
                    <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                    <button class="btn" onclick="destroyRow(${r.id})">删除</button>
                </td>
            </tr>`).join('');
            adminApi.pager('pager', res, load);
        });
    }

    function loadPositions(page = 1, pageSize = adminApi.pageSize) {
        positionPage = page;
        const f = {};
        const kw = document.getElementById('position-keyword').value.trim();
        if (kw) f.keyword = kw;
        adminApi.post('/api/admin/adPosition/search', {page, pageSize, ...f}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('positions-tbody');
            if (!rows.length) {
                if (page > 1) { loadPositions(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="5" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.name)}</td>
                <td><code>${adminApi.esc(r.code)}</code></td>
                <td>${adminApi.esc(r.adType ?? r.ad_type)}</td>
                <td>${r.maxCount ?? r.max_count ?? 1}</td>
            </tr>`).join('');
            adminApi.pager('positions-pager', res, loadPositions);
        });
    }

    document.getElementById('filter-form').addEventListener('submit', e => { e.preventDefault(); load(); });
    document.getElementById('f-reset').addEventListener('click', () => {
        document.querySelectorAll('#filter-form input, #filter-form select').forEach(el => el.value = '');
        load();
    });
    document.getElementById('position-filter-form').addEventListener('submit', e => { e.preventDefault(); loadPositions(); });
    document.getElementById('position-reset').addEventListener('click', () => {
        document.querySelectorAll('#position-filter-form input, #position-filter-form select').forEach(el => el.value = '');
        loadPositions();
    });

    load();
    loadPositions();

    function destroyRow(id) {
        if (!confirm('确认删除该广告？')) return;
        adminApi.post('/api/admin/ad/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
