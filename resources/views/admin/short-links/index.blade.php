@extends('admin.layouts.admin')

@section('title', '短链接')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.short-links.create') }}" class="btn bg-primary-500 text-white">新增短链</a>
        </div>
        <strong>短链接</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="短码" style="width:200px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th width="120">短码</th><th>目标 URL</th><th width="90">点击数</th><th width="170">过期时间</th><th width="170">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.short-links.index') }}";
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/shortLink/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td><code>/s/${adminApi.esc(r.shortCode ?? r.short_code)}</code></td>
                <td>${adminApi.esc(r.targetUrl ?? r.target_url)}</td>
                <td>${r.clickCount ?? r.click_count ?? 0}</td>
                <td>${adminApi.esc(r.expireAt ?? r.expire_at ?? '永不过期')}</td>
                <td>
                    <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                    <a class="btn" href="${base}/${r.id}/clicks">明细</a>
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

    function destroyRow(id) {
        if (!confirm('确认删除该短链？点击明细将一并删除。')) return;
        adminApi.post('/api/admin/shortLink/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
