@extends('admin.layouts.admin')

@section('title', '媒体库')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <button class="btn btn-primary btn-sm" onclick="alert('上传功能开发中（storage_config 驱动：local/oss/s3）')">上传文件</button>
        </div>
        <strong>媒体库</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="60">ID</th>
                <th>文件名</th>
                <th width="100">类型</th>
                <th width="100">大小</th>
                <th width="90">存储</th>
                <th width="170">上传时间</th>
                <th width="90">操作</th>
            </tr>
            </thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatSize(bytes) {
        bytes = Number(bytes ?? 0);
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    adminApi.post('/api/admin/attachment/search', {page: 1, pageSize: 20}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.fileName ?? r.file_name)}</td>
            <td>${adminApi.esc(r.mimeType ?? r.mime_type)}</td>
            <td>${formatSize(r.fileSize ?? r.file_size)}</td>
            <td>${adminApi.esc(r.storageDriver ?? r.storage_driver ?? 'local')}</td>
            <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
            <td><button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button></td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该附件？删除前系统将校验引用（见开发约定第三节）。')) return;
        adminApi.post('/api/admin/attachment/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
