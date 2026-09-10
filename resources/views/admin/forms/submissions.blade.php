@extends('admin.layouts.admin')

@section('title', '提交数据')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.forms.index') }}" class="btn btn-default">返回表单列表</a>
        </div>
        <strong>表单提交数据（#{{ $id }}）</strong>
    </div>

    {{-- 表格区域 --}}
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>提交数据（JSON）</th><th width="140">IP</th><th width="170">提交时间</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="4" class="text-gray-500">加载中…</td></tr></tbody>
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
        adminApi.post('/api/admin/formSubmission/search', {page, pageSize, formId: {{ $id }}}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="4" class="text-gray-500">暂无提交</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => {
                const data = r.submissionData ?? r.submission_data ?? {};
                const pretty = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
                return `<tr>
                    <td>${r.id}</td>
                    <td><pre style="margin:0">${adminApi.esc(pretty)}</pre></td>
                    <td>${adminApi.esc(r.submitterIp ?? r.submitter_ip ?? '')}</td>
                    <td>${adminApi.esc(r.createdAt ?? r.created_at ?? '-')}</td>
                </tr>`;
            }).join('');
            adminApi.pager('pager', res, load);
        });
    }

    load();
</script>
@endpush
