@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增短链' : '编辑短链')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增短链' : '编辑短链' }}</strong></div>
    <div class="panel-body">
        <form id="short-link-form">
            <div class="form-group">
                <label for="short_code">短码（留空自动生成）</label>
                <input type="text" class="form-control" id="short_code" name="short_code" pattern="[a-zA-Z0-9]{1,20}">
            </div>
            <div class="form-group">
                <label for="target_url">目标 URL</label>
                <input type="text" class="form-control" id="target_url" name="target_url" required>
            </div>
            <div class="form-group">
                <label for="title">标题 / 备注</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="expire_at">过期时间（留空永不过期）</label>
                <input type="text" class="form-control" id="expire_at" name="expire_at" placeholder="YYYY-MM-DD HH:mm:ss">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status" name="status">
                    <option value="1">启用</option>
                    <option value="0">停用</option>
                </select>
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.short-links.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/shortLink/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['short_code', 'target_url', 'title', 'expire_at'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.status !== undefined) document.getElementById('status').value = d.status;
        });
    }

    document.getElementById('short-link-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        if (!data.short_code) delete data.short_code; // 留空自动生成
        const req = id
            ? adminApi.put('/api/admin/shortLink/update', {id, ...data})
            : adminApi.post('/api/admin/shortLink/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.short-links.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
