@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增用户' : '编辑用户')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增用户' : '编辑用户' }}</strong></div>
    <div class="panel-body">
        <form id="user-form">
            <div class="form-group">
                <label for="name">昵称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">邮箱（登录凭据）</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">{{ $mode === 'create' ? '密码' : '新密码（留空则不修改）' }}</label>
                <input type="password" class="form-control" id="password" name="password" {{ $mode === 'create' ? 'required' : '' }}>
            </div>
            <div class="form-group">
                <label for="avatar">头像 URL</label>
                <input type="text" class="form-control" id="avatar" name="avatar">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status" name="status">
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>角色分配（决定该用户的操作权限与数据范围）</strong></div>
    <div class="panel-body">
        <div id="role-list" class="alert alert-info">角色加载中…</div>
        <p class="text-muted" style="font-size:12px">数据范围默认 self（仅本人数据）；如需 dept/all 需在绑定后单独调整。</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};
    const existingBindings = []; // [{id, role_id}] 已有绑定记录

    // 渲染角色勾选框（全部角色）
    adminApi.post('/api/admin/role/search', {page: 1, pageSize: 100}).then(res => {
        const box = document.getElementById('role-list');
        box.className = '';
        adminApi.rows(res).forEach(r => {
            box.insertAdjacentHTML('beforeend', `<label class="checkbox-inline" style="margin-right:16px">
                <input type="checkbox" class="role-item" value="${r.id}"> ${adminApi.esc(r.name)}
                <span class="text-muted">（${adminApi.esc(r.alias)}）</span>
            </label>`);
        });

        // 编辑模式：回显已绑定角色
        if (id) {
            adminApi.post('/api/admin/userRole/search', {page: 1, pageSize: 50, user_id: id}).then(res2 => {
                adminApi.rows(res2).forEach(b => {
                    const roleId = b.roleId ?? b.role_id;
                    existingBindings.push({id: b.id, role_id: roleId});
                    const el = box.querySelector(`.role-item[value="${roleId}"]`);
                    if (el) el.checked = true;
                });
            });
        }
    });

    // 用户基本信息回显
    if (id) {
        adminApi.get('/api/admin/user/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'email', 'avatar'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.status !== undefined) document.getElementById('status').value = d.status;
        });
    }

    document.getElementById('user-form').addEventListener('submit', async e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        if (!data.password) delete data.password; // 编辑时空密码不修改
        const res = id
            ? await adminApi.put('/api/admin/user/update', {id, ...data})
            : await adminApi.post('/api/admin/user/store', data);
        if (res.code !== 0) { alert(res.message || '保存失败'); return; }

        const uid = id ?? res.data?.id;
        if (uid) await syncRoles(uid);
        location.href = "{{ route('admin.users.index') }}";
    });

    // 同步角色绑定：勾选的追加（data_scope=self），取消的移除
    async function syncRoles(uid) {
        const checked = [...document.querySelectorAll('.role-item:checked')].map(i => Number(i.value));
        const bound = existingBindings.map(b => b.role_id);
        for (const b of existingBindings) {
            if (!checked.includes(b.role_id)) {
                await adminApi.post('/api/admin/userRole/destroy', {id: b.id});
            }
        }
        for (const roleId of checked) {
            if (!bound.includes(roleId)) {
                await adminApi.post('/api/admin/userRole/store', {user_id: uid, role_id: roleId, data_scope: 'self'});
            }
        }
    }
</script>
@endpush
