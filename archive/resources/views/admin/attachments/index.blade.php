@extends('admin.layouts.admin')

@section('title', '媒体库')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <input type="file" id="upload-input" hidden multiple>
            <button class="btn bg-primary-500 text-white" onclick="document.getElementById('upload-input').click()">上传文件</button>
        </div>
        <strong>媒体库</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>关键词</label>
            <input type="text" class="form-control" id="f-keyword" placeholder="文件名" style="width:200px">
        </div>
        <div class="form-group">
            <label>MIME 类型</label>
            <input type="text" class="form-control" id="f-mime" placeholder="如 image/jpeg" style="width:160px">
        </div>
        <button type="submit" class="btn bg-primary-500 text-white">查询</button>
        <button type="button" class="btn" id="f-reset">重置</button>
    </form>

    {{-- 表格区域 --}}
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
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
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

    // 上传（multipart，不走 adminApi 的 JSON 封装）
    document.getElementById('upload-input').addEventListener('change', async e => {
        const files = [...e.target.files];
        if (!files.length) return;
        for (const file of files) {
            const fd = new FormData();
            fd.append('file', file);
            const res = await fetch('/api/admin/attachment/upload', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: fd,
            }).then(r => r.json());
            if (res.code !== 0) {
                alert('上传失败（' + file.name + '）：' + (res.message || '未知错误'));
            }
        }
        e.target.value = '';
        load();
    });

    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const kw = document.getElementById('f-keyword').value.trim();
        if (kw) f.keyword = kw;
        const mime = document.getElementById('f-mime').value.trim();
        if (mime) f.mimeType = mime;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/attachment/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.fileName ?? r.file_name)}</td>
                <td>${adminApi.esc(r.mimeType ?? r.mime_type)}</td>
                <td>${formatSize(r.fileSize ?? r.file_size)}</td>
                <td>${adminApi.esc(r.storageDriver ?? r.storage_driver ?? 'local')}</td>
                <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
                <td><button class="btn" onclick="destroyRow(${r.id})">删除</button></td>
            </tr>`).join('');
            adminApi.pager('pager', res, load);
        });
    }

    document.getElementById('filter-form').addEventListener('submit', e => { e.preventDefault(); load(); });
    document.getElementById('f-reset').addEventListener('click', () => {
        document.querySelectorAll('#filter-form input, #filter-form select').forEach(el => el.value = '');
        load();
    });

    load();

    function destroyRow(id) {
        if (!confirm('确认删除该附件？删除前系统将校验引用（见开发约定第三节）。')) return;
        adminApi.post('/api/admin/attachment/destroy', {ids: [id]}).then(() => load(currentPage));
    }
</script>
@endpush
