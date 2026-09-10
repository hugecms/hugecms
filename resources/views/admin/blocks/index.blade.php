@extends('admin.layouts.admin')

@section('title', '区块管理')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.blocks.create') }}" class="btn btn-primary btn-sm">新增区块</a>
        </div>
        <strong>区块 / 组件</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>区块名称</th><th width="110">类型</th><th width="80">全局</th><th width="90">状态</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="6" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.blocks.index') }}";

    adminApi.post('/api/admin/block/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-muted">暂无数据</td></tr>'; return; }
        tbody.innerHTML = rows.map(r => `<tr>
            <td>${r.id}</td>
            <td>${adminApi.esc(r.blockName ?? r.block_name)}</td>
            <td>${adminApi.esc(r.blockType ?? r.block_type)}</td>
            <td>${(r.isGlobal ?? r.is_global) ? '是' : '否'}</td>
            <td>${(r.status ?? 1) ? '启用' : '停用'}</td>
            <td>
                <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                <button class="btn btn-sm" onclick="destroyRow(${r.id})">删除</button>
            </td>
        </tr>`).join('');
    });

    function destroyRow(id) {
        if (!confirm('确认删除该区块？')) return;
        adminApi.post('/api/admin/block/destroy', {id}).then(() => location.reload());
    }
</script>
@endpush
