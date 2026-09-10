@extends('admin.layouts.admin')

@section('title', '友情链接')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.friend-links.create') }}" class="btn bg-primary-500 text-white">新增友链</a>
        </div>
        <strong>友情链接（含审核流）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="网站名称" style="width:200px">
        </div>
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" id="f-status" style="width:140px">
                <option value="">全部</option>
                <option value="0">待审核</option>
                <option value="1">已审核</option>
                <option value="2">已拒绝</option>
            </select>
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>网站名称</th><th>URL</th><th width="100">分类</th><th width="90">状态</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.friend-links.index') }}";
    const statusText = {0: '待审核', 1: '已审核', 2: '已拒绝'};
    let currentPage = 1;

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
        adminApi.post('/api/admin/friendLink/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
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
            adminApi.pager('pager', res, load);
        });
    }

    document.getElementById('filter-form').addEventListener('submit', e => { e.preventDefault(); load(); });
    document.getElementById('f-reset').addEventListener('click', () => {
        document.querySelectorAll('#filter-form input, #filter-form select').forEach(el => el.value = '');
        load();
    });

    load();

    function approve(id) {
        adminApi.put('/api/admin/friendLink/update', {id, status: 1}).then(() => load(currentPage));
    }

    function destroyRow(id) {
        if (!confirm('确认删除该友链？')) return;
        adminApi.post('/api/admin/friendLink/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
