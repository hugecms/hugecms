@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增广告' : '编辑广告')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增广告' : '编辑广告' }}</strong></div>
    <div class="panel-body">
        <form id="ad-form">
            <div class="form-group">
                <label for="position_id">所属广告位</label>
                <select class="form-control" id="position_id" name="position_id" required></select>
            </div>
            <div class="form-group">
                <label for="title">广告标题</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="ad_type">广告类型</label>
                <select class="form-control" id="ad_type" name="ad_type">
                    <option value="image">图片</option>
                    <option value="text">文字</option>
                    <option value="video">视频</option>
                    <option value="html">HTML</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cover_image">广告图 / 视频封面 URL</label>
                <input type="text" class="form-control" id="cover_image" name="cover_image">
            </div>
            <div class="form-group">
                <label for="link_url">跳转链接</label>
                <input type="text" class="form-control" id="link_url" name="link_url">
            </div>
            <div class="form-group">
                <label for="start_time">投放开始时间（留空立即开始）</label>
                <input type="text" class="form-control" id="start_time" name="start_time" placeholder="YYYY-MM-DD HH:mm:ss">
            </div>
            <div class="form-group">
                <label for="end_time">投放结束时间（留空永久）</label>
                <input type="text" class="form-control" id="end_time" name="end_time" placeholder="YYYY-MM-DD HH:mm:ss">
            </div>
            <div class="form-group">
                <label for="sort">排序（越小越靠前）</label>
                <input type="number" class="form-control" id="sort" name="sort" value="0">
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    // 加载广告位下拉
    adminApi.post('/api/admin/adPosition/search', {page: 1, pageSize: 100}).then(res => {
        const sel = document.getElementById('position_id');
        adminApi.rows(res).forEach(p => {
            sel.insertAdjacentHTML('beforeend', `<option value="${p.id}">${adminApi.esc(p.name)}（${adminApi.esc(p.code)}）</option>`);
        });
        if (id) {
            adminApi.get('/api/admin/ad/show?id=' + id).then(res2 => {
                const d = res2.data ?? {};
                if (d.positionId ?? d.position_id) sel.value = d.positionId ?? d.position_id;
                ['title', 'cover_image', 'link_url', 'start_time', 'end_time', 'sort'].forEach(k => {
                    const el = document.getElementById(k);
                    if (el && d[k] !== undefined) el.value = d[k];
                });
                if (d.adType ?? d.ad_type) document.getElementById('ad_type').value = d.adType ?? d.ad_type;
            });
        }
    });

    document.getElementById('ad-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/ad/update', {id, ...data})
            : adminApi.post('/api/admin/ad/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.ads.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
