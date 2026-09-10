@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增角色' : '编辑角色')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增角色' : '编辑角色' }}</strong></div>
    <div class="panel-body">
        <form id="role-form">
            <div class="form-group">
                <label for="name">角色名称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="alias">角色标识</label>
                <input type="text" class="form-control" id="alias" name="alias" required>
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-default">返回</a>
        </form>

        @if ($mode === 'edit')
        <hr>
        <h4>权限分配</h4>
        <div id="permission-tree" class="alert alert-info">权限树加载中…（按模块分组勾选，经 rolePermission 接口保存）</div>
        <button class="btn btn-primary" id="save-perms">保存权限</button>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/role/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'alias', 'description'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
        });

        // 权限树：模块节点 + 操作节点
        adminApi.post('/api/admin/permission/search', {page: 1, pageSize: 200}).then(res => {
            const perms = adminApi.rows(res).filter(p => !(p.parentId ?? p.parent_id));
            const children = adminApi.rows(res).filter(p => (p.parentId ?? p.parent_id));
            const box = document.getElementById('permission-tree');
            box.className = '';
            box.innerHTML = perms.map(p => `
                <div style="margin-bottom:10px">
                    <label class="checkbox-inline"><strong>
                        <input type="checkbox" class="perm-group" data-id="${p.id}"> ${adminApi.esc(p.name)}
                    </strong></label>
                    <div style="padding-left:24px">
                        ${children.filter(c => (c.parentId ?? c.parent_id) === p.id).map(c => `
                            <label class="checkbox-inline">
                                <input type="checkbox" class="perm-item" value="${c.id}"> ${adminApi.esc(c.name)}
                            </label>`).join('')}
                    </div>
                </div>`).join('');

            // 全选联动
            box.querySelectorAll('.perm-group').forEach(g => g.addEventListener('change', () => {
                g.closest('div[style]').querySelectorAll('.perm-item').forEach(i => i.checked = g.checked);
            }));

            // TODO: 回显该角色已授权限（rolePermission/search），保存经 rolePermission/store 批量提交
        });
    }

    document.getElementById('role-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/role/update', {id, ...data})
            : adminApi.post('/api/admin/role/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.roles.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
