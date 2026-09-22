@extends('admin.layouts.admin')

@section('title', '评论管理')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>评论列表</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="评论内容" style="width:200px">
        </div>
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" id="f-status" style="width:140px">
                <option value="">全部</option>
                <option value="pending">待审核</option>
                <option value="approved">已通过</option>
                <option value="spam">垃圾</option>
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
                <th>评论者</th>
                <th>内容</th>
                <th width="90">状态</th>
                <th width="170">时间</th>
                <th width="160">操作</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const statusText = {pending: '待审核', approved: '已通过', spam: '垃圾', trash: '回收站'};
    let currentPage = 1;
    const ADMIN_ID = {{ auth()->id() ?? 'null' }};
    const ADMIN_NAME = {{ json_encode(auth()->user()->name ?? '管理员', JSON_UNESCAPED_UNICODE) }};

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
        adminApi.post('/api/admin/comment/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="6" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.authorName ?? r.author_name ?? (r.userId ?? r.user_id ?? '游客'))}</td>
                <td>${adminApi.esc(r.content)}</td>
                <td><span class="label">${adminApi.esc(statusText[r.status] ?? r.status)}</span></td>
                <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
                <td>
                    <button class="btn" onclick="replyRow(${r.id}, ${r.contentId ?? r.content_id}, ${(r.userId ?? r.user_id) ?? null})">回复</button>
                    ${r.status === 'pending' ? `<button class="btn" onclick="setStatus(${r.id}, 'approved')">通过</button>` : ''}
                    ${r.status !== 'spam' ? `<button class="btn" onclick="setStatus(${r.id}, 'spam')">标垃圾</button>` : ''}
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

    function setStatus(id, status) {
        adminApi.put('/api/admin/comment/update', {id, status}).then(() => load(currentPage));
    }

    // 管理员回复：以当前登录用户身份插入一条已通过的子评论
    function replyRow(id, contentId, replyTo) {
        const content = prompt('回复内容：');
        if (!content) return;
        adminApi.post('/api/admin/comment/store', {
            content_id: contentId,
            parent_id: id,
            reply_to_user_id: replyTo,
            user_id: ADMIN_ID,
            author_name: ADMIN_NAME,
            content,
            status: 'approved',
        }).then(() => load(currentPage));
    }

    function destroyRow(id) {
        if (!confirm('确认删除该评论？')) return;
        adminApi.post('/api/admin/comment/destroy', {ids: [id]}).then(() => load(currentPage));
    }

    load();
</script>
@endpush
