@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增广告位' : '编辑广告位')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增广告位' : '编辑广告位' }}</strong></div>
    <div class="panel-body">
        <form id="position-form">
            <div class="form-group">
                <label for="name">广告位名称（如：首页Banner）</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="code">广告位代码（模板调用用，如 home_banner）</label>
                <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="form-group">
                <label for="ad_type">支持的广告类型</label>
                <select class="form-control" id="ad_type" name="ad_type">
                    <option value="image">图片</option>
                    <option value="text">文字</option>
                    <option value="video">视频</option>
                    <option value="html">HTML</option>
                </select>
            </div>
            <div class="form-group">
                <label for="width">建议宽度（像素）</label>
                <input type="number" class="form-control" id="width" name="width" value="0">
            </div>
            <div class="form-group">
                <label for="height">建议高度（像素）</label>
                <input type="number" class="form-control" id="height" name="height" value="0">
            </div>
            <div class="form-group">
                <label for="max_count">最多展示广告数</label>
                <input type="number" class="form-control" id="max_count" name="max_count" value="1">
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status" name="status">
                    <option value="1">启用</option>
                    <option value="0">停用</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.ad-positions.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/adPosition/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'code', 'description'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            ['width', 'height', 'max_count', 'status'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            if (d.adType ?? d.ad_type) document.getElementById('ad_type').value = d.adType ?? d.ad_type;
        });
    }

    document.getElementById('position-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/adPosition/update', {id, ...data})
            : adminApi.post('/api/admin/adPosition/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.ad-positions.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
