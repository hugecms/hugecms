@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增菜单' : '编辑菜单')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增菜单' : '编辑菜单' }}</strong></div>
    <div class="panel-body">
        <form id="menu-form">
            <div class="form-group">
                <label for="name">菜单名称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="alias">菜单标识（模板调用用，如 main_nav）</label>
                <input type="text" class="form-control" id="alias" name="alias" required>
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-default">返回</a>
        </form>

        @if ($mode === 'edit')
        <hr>
        <h4>菜单项</h4>
        <form id="item-form" class="flex flex-wrap gap-2 items-end" style="margin-bottom:12px">
            <div class="form-group">
                <input type="text" class="form-control" id="item-title" placeholder="标题" required style="width:140px">
            </div>
            <div class="form-group">
                <select class="form-control" id="item-link-type" style="width:110px">
                    <option value="custom">自定义链接</option>
                    <option value="content">指向内容</option>
                    <option value="term">指向分类</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="item-link-value" placeholder="URL 或 内容/分类ID" required style="width:190px">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" id="item-sort" value="0" title="排序" style="width:80px">
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">添加菜单项</button>
        </form>
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>标题</th><th width="90">链接类型</th><th>链接值</th><th width="70">排序</th><th width="70">启用</th><th width="90">操作</th></tr></thead>
            <tbody id="items-tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/navMenu/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'alias', 'description'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
        });
        loadItems();
    }

    function loadItems() {
        adminApi.post('/api/admin/navItem/search', {page: 1, pageSize: 200, menu_id: id}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('items-tbody');
            if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无菜单项</td></tr>'; return; }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.title)}</td>
                <td>${adminApi.esc(r.linkType ?? r.link_type)}</td>
                <td>${adminApi.esc(r.linkValue ?? r.link_value)}</td>
                <td>${r.sort ?? 0}</td>
                <td>${(r.isActive ?? r.is_active) ? '是' : '否'}</td>
                <td>
                    <button class="btn" onclick="toggleItem(${r.id}, ${((r.isActive ?? r.is_active) ? 0 : 1)})">${(r.isActive ?? r.is_active) ? '停用' : '启用'}</button>
                    <button class="btn" onclick="destroyItem(${r.id})">删除</button>
                </td>
            </tr>`).join('');
        });
    }

    // 添加菜单项
    document.getElementById('item-form')?.addEventListener('submit', e => {
        e.preventDefault();
        const data = {
            menu_id: id,
            parent_id: 0,
            title: document.getElementById('item-title').value.trim(),
            link_type: document.getElementById('item-link-type').value,
            link_value: document.getElementById('item-link-value').value.trim(),
            sort: Number(document.getElementById('item-sort').value || 0),
            open_type: 0,
            is_active: 1,
        };
        adminApi.post('/api/admin/navItem/store', data).then(res => {
            if (res.code === 0) { document.getElementById('item-title').value = ''; document.getElementById('item-link-value').value = ''; loadItems(); }
            else alert(res.message || '添加失败');
        });
    });

    function toggleItem(itemId, isActive) {
        adminApi.put('/api/admin/navItem/update', {id: itemId, is_active: isActive}).then(loadItems);
    }

    function destroyItem(itemId) {
        if (!confirm('确认删除该菜单项？')) return;
        adminApi.post('/api/admin/navItem/destroy', {ids: [itemId]}).then(loadItems);
    }

    document.getElementById('menu-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/navMenu/update', {id, ...data})
            : adminApi.post('/api/admin/navMenu/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.menus.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
