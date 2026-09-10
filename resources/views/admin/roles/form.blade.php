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
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-default">返回</a>
        </form>

        @if ($mode === 'edit')
        <hr>
        <h4>权限分配</h4>
        <div id="permission-tree"><p class="text-gray-500">权限树加载中…</p></div>
        <button class="btn bg-primary-500 text-white" id="save-perms">保存权限</button>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};
    const pick = (o, snake) => o?.[snake] ?? o?.[snake.replace(/_([a-z])/g, (_, c) => c.toUpperCase())];
    const existingPerms = []; // [{id, permissionId}] 已绑定记录

    if (id) {
        adminApi.get('/api/admin/role/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'alias', 'description'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
        });

        // 权限树：模块节点 + 操作节点（与 CmsSeeder 种子树同构）
        adminApi.post('/api/admin/permission/search', {page: 1, pageSize: 200}).then(res => {
            const all = adminApi.rows(res);
            const modules = all.filter(p => !Number(pick(p, 'parent_id') ?? 0));
            const box = document.getElementById('permission-tree');
            box.innerHTML = modules.map(p => `
                <div class="perm-group" data-id="${p.id}" style="margin-bottom:10px">
                    <label class="inline-flex items-center mr-3"><strong>
                        <input type="checkbox" class="perm-group-check"> ${adminApi.esc(p.name)}
                    </strong></label>
                    <div style="padding-left:24px">
                        ${all.filter(c => Number(pick(c, 'parent_id')) === Number(p.id)).map(c => `
                            <label class="inline-flex items-center mr-3" style="margin-right:14px">
                                <input type="checkbox" class="perm-item" value="${c.id}" title="${adminApi.esc(c.code)}"> ${adminApi.esc(c.name)}
                            </label>`).join('')}
                    </div>
                </div>`).join('');

            // 组全选联动 + 子项反查组状态
            box.querySelectorAll('.perm-group-check').forEach(g => {
                g.addEventListener('change', () => {
                    g.closest('.perm-group').querySelectorAll('.perm-item').forEach(i => i.checked = g.checked);
                });
            });
            box.addEventListener('change', e => {
                if (e.target.classList.contains('perm-item')) {
                    const group = e.target.closest('.perm-group');
                    const items = [...group.querySelectorAll('.perm-item')];
                    group.querySelector('.perm-group-check').checked = items.every(i => i.checked);
                }
            });

            // 回显该角色已授权限（is_denied=1 的拒绝项不勾选）
            adminApi.post('/api/admin/rolePermission/search', {page: 1, pageSize: 500, role_id: id}).then(res2 => {
                adminApi.rows(res2).forEach(b => {
                    const permissionId = Number(pick(b, 'permission_id'));
                    if (Number(pick(b, 'is_denied') ?? 0) === 1) return;
                    existingPerms.push({id: b.id, permissionId});
                    const el = box.querySelector(`.perm-item[value="${permissionId}"]`);
                    if (el) el.checked = true;
                });
                box.querySelectorAll('.perm-group').forEach(group => {
                    const items = [...group.querySelectorAll('.perm-item')];
                    if (items.length) group.querySelector('.perm-group-check').checked = items.every(i => i.checked);
                });
            });
        });

        // 保存权限：勾选的追加（is_denied=0），取消的移除
        document.getElementById('save-perms')?.addEventListener('click', async () => {
            const checked = [...document.querySelectorAll('.perm-item:checked')].map(i => Number(i.value));
            const bound = existingPerms.map(p => p.permissionId);
            for (const p of existingPerms) {
                if (!checked.includes(p.permissionId)) {
                    await adminApi.post('/api/admin/rolePermission/destroy', {ids: [p.id]});
                }
            }
            for (const permissionId of checked) {
                if (!bound.includes(permissionId)) {
                    await adminApi.post('/api/admin/rolePermission/store', {roleId: id, permissionId, isDenied: 0});
                }
            }
            alert('权限已保存');
            location.reload();
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
