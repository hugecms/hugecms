@extends('admin.layouts.admin')

@section('title', '区块管理')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.blocks.create') }}" class="btn bg-primary-500 text-white">新增区块</a>
        </div>
        <strong>区块 / 组件</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="区块名称" style="width:200px">
        </div>
        <div class="form-group">
            <label>类型</label>
            <select class="form-control" id="f-type" style="width:150px">
                <option value="">全部</option>
                <option value="header">header</option>
                <option value="footer">footer</option>
                <option value="banner">banner</option>
                <option value="content">content</option>
                <option value="sidebar">sidebar</option>
                <option value="custom">custom</option>
            </select>
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>区块名称</th><th width="110">类型</th><th width="80">全局</th><th width="90">状态</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.blocks.index') }}";
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        const type = document.getElementById('f-type').value;
        if (type !== '') f.blockType = type;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/block/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.blockName ?? r.block_name)}</td>
                <td>${adminApi.esc(r.blockType ?? r.block_type)}</td>
                <td>${(r.isGlobal ?? r.is_global) ? '是' : '否'}</td>
                <td>${(r.status ?? 1) ? '启用' : '停用'}</td>
                <td>
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

    function destroyRow(id) {
        if (!confirm('确认删除该区块？')) return;
        adminApi.post('/api/admin/block/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
