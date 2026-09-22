@extends('admin.layouts.admin')

@section('title', '回收站')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>回收站（快照含全部级联关联，恢复约定见开发约定第一节）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>对象类型</label>
            <select class="form-control" id="f-type" style="width:180px">
                <option value="">全部</option>
                <option value="content">内容</option>
                <option value="term">分类项</option>
                <option value="attachment">附件</option>
                <option value="user">用户</option>
                <option value="form_submission">表单提交</option>
                <option value="comment">评论</option>
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
                <th width="110">对象类型</th>
                <th width="80">对象ID</th>
                <th>快照摘要</th>
                <th width="100">保留天数</th>
                <th width="170">删除时间</th>
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
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const type = document.getElementById('f-type').value;
        if (type !== '') f.targetType = type;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/recycleBin/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">回收站为空</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => {
                const snap = r.originalData ?? r.original_data ?? {};
                const title = snap.content?.title ?? JSON.stringify(snap).slice(0, 50);
                const expire = r.expireAt ?? r.expire_at;
                return `<tr>
                    <td>${r.id}</td>
                    <td>${adminApi.esc(r.targetType ?? r.target_type)}</td>
                    <td>${adminApi.esc(r.targetId ?? r.target_id)}</td>
                    <td>${adminApi.esc(title)}</td>
                    <td>${r.retentionDays ?? r.retention_days ?? 30}${expire ? '<br><span class="text-gray-500">至 ' + adminApi.esc(expire) + '</span>' : ''}</td>
                    <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
                    <td>
                        <button class="btn" onclick="restoreRow(${r.id})">恢复</button>
                        <button class="btn" onclick="destroyRow(${r.id})">彻底删除</button>
                    </td>
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

    function restoreRow(id) {
        if (!confirm('确认恢复？将按快照恢复内容及其关联数据。')) return;
        adminApi.post('/api/admin/recycleBin/restore', {id}).then(() => load(currentPage));
    }

    function destroyRow(id) {
        if (!confirm('彻底删除不可恢复（内容、评论、关联将一并物理清除），确认继续？')) return;
        adminApi.post('/api/admin/recycleBin/purge', {id}).then(() => load(currentPage));
    }
</script>
@endpush
