@extends('admin.layouts.admin')

@section('title', '权限管理')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>权限树（种子数据来自 CmsSeeder，9 模块 / 44 操作项）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="权限名称" style="width:220px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th width="80">父级</th><th>名称</th><th width="220">权限代码</th><th width="110">模块</th><th width="70">排序</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
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
        adminApi.post('/api/admin/permission/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => {
                const isModule = !(r.parentId ?? r.parent_id);
                return `<tr ${isModule ? 'style="background:#f6f8fa;font-weight:bold"' : ''}>
                    <td>${r.id}</td>
                    <td>${r.parentId ?? r.parent_id ?? 0}</td>
                    <td>${isModule ? '' : '└ '}${adminApi.esc(r.name)}</td>
                    <td><code>${adminApi.esc(r.code)}</code></td>
                    <td>${adminApi.esc(r.module)}</td>
                    <td>${r.sort ?? 0}</td>
                </tr>`;
            }).join('');
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
