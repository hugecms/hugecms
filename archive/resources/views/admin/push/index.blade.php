@extends('admin.layouts.admin')

@section('title', '内容推送')

@section('content')
<div class="panel">
    {{-- 标题栏和按钮 --}}
    <div class="panel-heading">
        <strong>内容分发推送（公众号 / 小程序 / RSS / 站长平台；执行走 Laravel 队列）</strong>
    </div>

    {{-- 筛选栏 --}}
    <form class="filter-bar" id="filter-form">
        <div class="form-group">
            <label>状态</label>
            <select class="form-control" id="f-status" style="width:160px">
                <option value="">全部</option>
                <option value="pending">pending 待推送</option>
                <option value="processing">processing 处理中</option>
                <option value="success">success 成功</option>
                <option value="failed">failed 失败</option>
            </select>
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
                <th width="70">内容ID</th>
                <th width="120">推送目标</th>
                <th width="90">状态</th>
                <th width="80">重试</th>
                <th>错误信息</th>
                <th width="170">完成时间</th>
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
    let currentPage = 1;

    function collectFilters() {
        const f = {};
        const status = document.getElementById('f-status').value;
        if (status !== '') f.status = status;
        return f;
    }

    function load(page = 1, pageSize = adminApi.pageSize) {
        currentPage = page;
        adminApi.post('/api/admin/contentPushQueue/search', {page, pageSize, ...collectFilters()}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('tbody');
            if (!rows.length) {
                if (page > 1) { load(page - 1); return; }
                tbody.innerHTML = '<tr><td colspan="7" class="text-gray-500">暂无推送记录</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(r => `<tr>
                <td>${r.id}</td>
                <td>${r.contentId ?? r.content_id}</td>
                <td>${adminApi.esc(r.pushType ?? r.push_type)}</td>
                <td><span class="label">${adminApi.esc(r.status)}</span></td>
                <td>${r.retryCount ?? r.retry_count ?? 0}/${r.maxRetries ?? r.max_retries ?? 3}</td>
                <td>${adminApi.esc((r.errorMessage ?? r.error_message ?? '').slice(0, 50))}</td>
                <td>${adminApi.esc(r.finishedAt ?? r.finished_at ?? '-')}</td>
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
</script>
@endpush
