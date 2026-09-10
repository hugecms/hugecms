@extends('admin.layouts.admin')

@section('title', '编辑站点')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>编辑站点</strong></div>
    <div class="panel-body">
        <form id="site-form">
            <div class="form-group">
                <label for="site_name">站点名称</label>
                <input type="text" class="form-control" id="site_name" name="site_name" required>
            </div>
            <div class="form-group">
                <label for="site_code">站点代码</label>
                <input type="text" class="form-control" id="site_code" name="site_code" required>
            </div>
            <div class="form-group">
                <label for="domain">主域名</label>
                <input type="text" class="form-control" id="domain" name="domain" placeholder="www.example.com" required>
            </div>
            <div class="form-group">
                <label for="site_logo">站点 Logo URL</label>
                <input type="text" class="form-control" id="site_logo" name="site_logo">
            </div>
            <div class="form-group">
                <label for="favicon">站点图标 URL</label>
                <input type="text" class="form-control" id="favicon" name="favicon">
            </div>
            <div class="form-group">
                <label for="language">默认语言</label>
                <input type="text" class="form-control" id="language" name="language" value="zh_CN">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status" name="status">
                    <option value="1">启用</option>
                    <option value="0">停用</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.sites.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/site/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['site_name', 'site_code', 'domain', 'site_logo', 'favicon', 'language'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.status !== undefined) document.getElementById('status').value = d.status;
        });
    }

    document.getElementById('site-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        adminApi.put('/api/admin/site/update', {id, ...data}).then(res => {
            if (res.code === 0) location.href = "{{ route('admin.sites.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
