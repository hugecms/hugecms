@extends('admin.layouts.admin')

@section('title', '评论管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="btn-group" id="status-filter">
            <button type="button" class="btn btn-sm btn-primary" data-status="">全部</button>
            <button type="button" class="btn btn-sm" data-status="pending">待审核</button>
            <button type="button" class="btn btn-sm" data-status="approved">已通过</button>
            <button type="button" class="btn btn-sm" data-status="spam">垃圾</button>
        </div>
    </div>
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
            <tbody id="tbody"><tr><td colspan="6" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStatus = '';
    const ADMIN_ID = {{ auth()->id() ?? 'null' }};
    const ADMIN_NAME = {{ json_encode(auth()->user()->name ?? '管理员', JSON_UNESCAPED_UNICODE) }};

    function load() {
        adminApi.post('/api/admin/comment/search', {page: 1, pageSize: 20, status: currentStatus}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-muted">暂无数据</td></tr>'; return; }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.authorName ?? r.author_name ?? (r.userId ?? r.user_id ?? '游客'))}</td>
                <td>${adminApi.esc(r.content)}</td>
                <td><span class="label">${adminApi.esc(r.status)}</span></td>
                <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
                <td>
                    <button class="btn btn-sm" onclick="replyRow(${r.id}, ${r.contentId ?? r.content_id}, ${(r.userId ?? r.user_id) ?? null})">回复</button>
                    ${r.status === 'pending' ? `<button class="btn btn-sm" onclick="setStatus(${r.id}, 'approved')">通过</button>` : ''}
                    ${r.status !== 'spam' ? `<button class="btn btn-sm" onclick="setStatus(${r.id}, 'spam')">标垃圾</button>` : ''}
                    <button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>
                </td>
            </tr>`).join('');
        });
    }

    function setStatus(id, status) {
        adminApi.put('/api/admin/comment/update', {id, status}).then(load);
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
        }).then(load);
    }

    function destroyRow(id) {
        if (!confirm('确认删除该评论？')) return;
        adminApi.post('/api/admin/comment/destroy', {id}).then(load);
    }

    document.querySelectorAll('#status-filter button').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#status-filter button').forEach(b => b.classList.remove('btn-primary'));
            btn.classList.add('btn-primary');
            currentStatus = btn.dataset.status;
            load();
        });
    });

    load();
</script>
@endpush
