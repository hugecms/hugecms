@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增重定向' : '编辑重定向')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增重定向' : '编辑重定向' }}</strong></div>
    <div class="panel-body">
        <form id="redirect-form">
            <div class="form-group">
                <label for="source_path">来源路径（站内相对路径，以 / 开头）</label>
                <input type="text" class="form-control" id="source_path" name="source_path" placeholder="/old-post-url" required>
            </div>
            <div class="form-group">
                <label for="target_path">目标路径（相对路径或完整 URL）</label>
                <input type="text" class="form-control" id="target_path" name="target_path" placeholder="/new-post-url" required>
            </div>
            <div class="form-group">
                <label for="status_code">状态码</label>
                <select class="form-control" id="status_code" name="status_code">
                    <option value="301">301 永久重定向</option>
                    <option value="302">302 临时重定向</option>
                </select>
            </div>
            <div class="form-group">
                <label for="remark">备注</label>
                <input type="text" class="form-control" id="remark" name="remark" placeholder="如：slug 改版迁移">
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.redirects.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/redirect/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['source_path', 'target_path', 'remark'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.statusCode ?? d.status_code) document.getElementById('status_code').value = d.statusCode ?? d.status_code;
        });
    }

    document.getElementById('redirect-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/redirect/update', {id, ...data})
            : adminApi.post('/api/admin/redirect/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.redirects.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
