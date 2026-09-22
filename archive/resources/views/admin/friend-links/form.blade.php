@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增友链' : '编辑友链')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增友链' : '编辑友链' }}</strong></div>
    <div class="panel-body">
        <form id="friend-link-form">
            <div class="form-group">
                <label for="site_name">网站名称</label>
                <input type="text" class="form-control" id="site_name" name="site_name" required>
            </div>
            <div class="form-group">
                <label for="site_url">网站 URL</label>
                <input type="text" class="form-control" id="site_url" name="site_url" required>
            </div>
            <div class="form-group">
                <label for="logo_url">Logo URL</label>
                <input type="text" class="form-control" id="logo_url" name="logo_url">
            </div>
            <div class="form-group">
                <label for="category">分类</label>
                <input type="text" class="form-control" id="category" name="category" value="default">
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="form-group">
                <label for="contact_email">联系邮箱</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email">
            </div>
            <div class="form-group">
                <label for="sort">排序</label>
                <input type="number" class="form-control" id="sort" name="sort" value="0">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status" name="status">
                    <option value="0">待审核</option>
                    <option value="1">已审核</option>
                    <option value="2">已拒绝</option>
                </select>
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.friend-links.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/friendLink/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['site_name', 'site_url', 'logo_url', 'category', 'description', 'contact_email', 'sort'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.status !== undefined) document.getElementById('status').value = d.status;
        });
    }

    document.getElementById('friend-link-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/friendLink/update', {id, ...data})
            : adminApi.post('/api/admin/friendLink/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.friend-links.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
