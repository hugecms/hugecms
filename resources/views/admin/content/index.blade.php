@extends('admin.layouts.admin')

@section('title', '内容管理')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.contents.create') }}" class="btn bg-primary-500 text-white">新增内容</a>
        </div>
        <strong>内容列表</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="标题" style="width:200px">
        </div>
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" id="f-status" style="width:140px">
                <option value="">全部</option>
                <option value="draft">草稿</option>
                <option value="pending">定时待发</option>
                <option value="published">已发布</option>
                <option value="archived">已归档</option>
                <option value="trash">回收站</option>
            </select>
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
                <th>标题</th>
                <th width="90">状态</th>
                <th width="80">浏览</th>
                <th width="80">评论</th>
                <th width="170">发布时间</th>
                <th width="130">操作</th>
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
    const base = "{{ route('admin.contents.index') }}";
    const statusText = {draft: '草稿', pending: '定时待发', published: '已发布', archived: '已归档', trash: '回收站'};
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
        adminApi.post('/api/admin/content/search', {page, pageSize, ...collectFilters()}).then(res => {
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
                <td><span class="label">${adminApi.esc(statusText[r.status] ?? r.status)}</span></td>
                <td>${r.views ?? 0}</td>
                <td>${r.commentCount ?? r.comment_count ?? 0}</td>
                <td>${adminApi.esc(r.publishedAt ?? r.published_at ?? '-')}</td>
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
        if (!confirm('确认删除？删除后将进入回收站。')) return;
        adminApi.post('/api/admin/content/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
