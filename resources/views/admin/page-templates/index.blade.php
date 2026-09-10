@extends('admin.layouts.admin')

@section('title', '页面模板')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.page-templates.create') }}" class="btn bg-primary-500 text-white">新增模板</a>
        </div>
        <strong>页面模板</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="模板名称" style="width:200px">
        </div>
        <div class="form-group">
            <label>类别</label>
            <select class="form-control" id="f-category" style="width:140px">
                <option value="">全部</option>
                <option value="page">page 页面</option>
                <option value="post">post 文章</option>
                <option value="term">term 分类</option>
            </select>
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>模板名称</th><th width="140">代码</th><th width="90">类别</th><th width="70">默认</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.page-templates.index') }}";
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        const category = document.getElementById('f-category').value;
        if (category !== '') f.category = category;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/pageTemplate/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.templateName ?? r.template_name)}</td>
                <td>${adminApi.esc(r.templateCode ?? r.template_code)}</td>
                <td>${adminApi.esc(r.category)}</td>
                <td>${(r.isDefault ?? r.is_default) ? '是' : '否'}</td>
                <td>
                    <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                    ${!(r.isSystem ?? r.is_system) ? `<button class="btn" onclick="destroyRow(${r.id})">删除</button>` : ''}
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
        if (!confirm('确认删除该模板？')) return;
        adminApi.post('/api/admin/pageTemplate/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
