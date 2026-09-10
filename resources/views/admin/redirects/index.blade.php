@extends('admin.layouts.admin')

@section('title', '重定向')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.redirects.create') }}" class="btn bg-primary-500 text-white">新增重定向</a>
        </div>
        <strong>重定向规则（slug 改版 / 栏目迁移的 SEO 保护）</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>来源路径</th><th>目标路径</th><th width="80">状态码</th><th width="80">命中</th><th width="170">最后命中</th><th width="90">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="7" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.redirects.index') }}";

    adminApi.post('/api/admin/redirect/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.sourcePath ?? r.source_path)}</td>
            <td>${adminApi.esc(r.targetPath ?? r.target_path)}</td>
            <td>${r.statusCode ?? r.status_code ?? 301}</td>
            <td>${r.hits ?? 0}</td>
            <td>${adminApi.esc(r.lastHitAt ?? r.last_hit_at ?? '-')}</td>
            <td>
                <a class="btn" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该重定向规则？')) return;
        adminApi.post('/api/admin/redirect/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
