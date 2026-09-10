@extends('admin.layouts.admin')

@section('title', '点击明细')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.short-links.index') }}" class="btn btn-default">返回短链列表</a>
        </div>
        <strong>短链接点击明细（#{{ $id }}，只增不改）</strong>
    </div>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th width="140">IP</th><th>UA</th><th>来源页</th><th width="170">点击时间</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="5" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        {{-- 分页栏 --}}
        <div class="pager" id="pager"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/shortLinkClick/search', {page, pageSize, shortLinkId: {{ $id }}}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="5" class="text-gray-500">暂无点击</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.clickIp ?? r.click_ip)}</td>
                <td>${adminApi.esc((r.userAgent ?? r.user_agent ?? '').slice(0, 60))}</td>
                <td>${adminApi.esc((r.referer ?? '').slice(0, 60))}</td>
                <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
            </tr>`).join('');
            adminApi.pager('pager', res, load);
        });
    }

    load();
</script>
@endpush
