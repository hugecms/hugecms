@extends('admin.layouts.admin')

@section('title', '重定向')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.redirects.create') }}" class="btn bg-primary-500 text-white">新增重定向</a>
        </div>
        <strong>重定向规则（slug 改版 / 栏目迁移的 SEO 保护）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="来源路径" style="width:200px">
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
            <thead><tr><th width="60">ID</th><th>来源路径</th><th>目标路径</th><th width="80">状态码</th><th width="80">命中</th><th width="170">最后命中</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.redirects.index') }}";
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
        adminApi.post('/api/admin/redirect/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.sourcePath ?? r.source_path)}</td>
                <td>${adminApi.esc(r.targetPath ?? r.target_path)}</td>
                <td>${r.statusCode ?? r.status_code ?? 301}</td>
                <td>${r.hits ?? 0}</td>
                <td>${adminApi.esc(r.lastHitAt ?? r.last_hit_at ?? '-')}</td>
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
        if (!confirm('确认删除该重定向规则？')) return;
        adminApi.post('/api/admin/redirect/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
