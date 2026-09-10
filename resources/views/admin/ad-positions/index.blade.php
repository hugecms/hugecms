@extends('admin.layouts.admin')

@section('title', '广告位')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.ad-positions.create') }}" class="btn bg-primary-500 text-white">新增广告位</a>
        </div>
        <strong>广告位</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>名称</th><th width="140">代码</th><th width="100">类型</th><th width="90">最多展示</th><th width="120">建议尺寸</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.ad-positions.index') }}";

    adminApi.post('/api/admin/adPosition/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.name)}</td>
            <td><code>${adminApi.esc(r.code)}</code></td>
            <td>${adminApi.esc(r.adType ?? r.ad_type)}</td>
            <td>${r.maxCount ?? r.max_count ?? 1}</td>
            <td>${r.width ?? 0} × ${r.height ?? 0}</td>
            <td>
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该广告位？其下广告将一并删除。')) return;
        adminApi.post('/api/admin/adPosition/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
