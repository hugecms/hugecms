@extends('admin::layout')

@section('title', '用户管理 - 管理后台')
@section('page-title', '用户管理')

@section('content')
    {{-- 标题区 --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-1">用户管理</h1>
            <p class="text-muted small mb-0">管理系统注册用户</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">+ 新建用户</a>
    </div>

    {{-- 筛选区 --}}
    <div class="card mb-3">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.user') }}" class="row g-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label mb-1">邮箱</label>
                    <input type="email" name="email" value="{{ $filters['email'] ?? '' }}" class="form-control form-control-sm" style="width: 200px;" placeholder="精确匹配">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1">用户名</label>
                    <input type="text" name="name" value="{{ $filters['name'] ?? '' }}" class="form-control form-control-sm" style="width: 160px;" placeholder="模糊匹配">
                </div>
                <div class="col-auto">
                    <label class="form-label mb-1">状态</label>
                    <select name="status" class="form-select form-select-sm" style="width: 120px;">
                        <option value="">全部</option>
                        @foreach ($statusOptions as $option)
                            <option value="{{ $option['value'] }}" {{ (string) ($filters['status'] ?? '') === (string) $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">查询</button>
                    <a href="{{ route('admin.user') }}" class="btn btn-outline-secondary btn-sm">重置</a>
                </div>
            </form>
        </div>
    </div>

    {{-- 列表区 --}}
    <div class="card">
        <div class="card-body">
            <form id="batch-form" method="POST" action="/admin/user" data-confirm="确认删除选中的用户？">
                @csrf
                @method('DELETE')
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">共 {{ $users['total'] ?? 0 }} 条</span>
                    <button type="submit" id="batch-btn" class="btn btn-danger btn-sm d-none" disabled>批量删除 (<span id="batch-count">0</span>)</button>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 36px;"><input type="checkbox" id="check-all"></th>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>邮箱</th>
                                <th>状态</th>
                                <th>注册时间</th>
                                <th class="text-end">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users['data'] as $user)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $user['id'] }}" class="row-check"></td>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <span class="badge rounded-pill {{ (int) $user['status'] === 1 ? 'bg-success' : 'bg-secondary' }}">{{ (int) $user['status'] === 1 ? '启用' : '禁用' }}</span>
                                </td>
                                <td>{{ $user['created_at'] }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.user.edit.{id}', $user['id']) }}" class="link-primary text-decoration-none me-2">编辑</a>
                                    <form method="POST" action="/admin/user" class="d-inline" onsubmit="return confirm('确认删除该用户？')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $user['id'] }}">
                                        <button type="submit" class="btn btn-link btn-sm link-danger text-decoration-none p-0">删除</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">暂无数据</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </form>

            {{-- 分页区 --}}
            @if (($users['last_page'] ?? 1) > 1 || ($users['total'] ?? 0) > 10)
            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <span class="text-muted small">共 {{ $users['total'] ?? 0 }} 条</span>
                <div class="d-flex align-items-center gap-3">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location = updateQueryParam('pageSize', this.value)">
                        @foreach ([10, 20, 50] as $size)
                            <option value="{{ $size }}" {{ $pageSize === $size ? 'selected' : '' }}>{{ $size }} 条/页</option>
                        @endforeach
                    </select>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item {{ $users['current_page'] <= 1 ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $users['current_page'] - 1]) }}">‹</a>
                        </li>
                        @for ($p = 1; $p <= $users['last_page']; $p++)
                            <li class="page-item {{ $p === $users['current_page'] ? 'active' : '' }}">
                                <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $p]) }}">{{ $p }}</a>
                            </li>
                        @endfor
                        <li class="page-item {{ $users['current_page'] >= $users['last_page'] ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $users['current_page'] + 1]) }}">›</a>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateQueryParam(key, value) {
            const url = new URL(window.location.href);
            url.searchParams.set(key, value);
            url.searchParams.set('page', '1');
            return url.toString();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const checkAll = document.getElementById('check-all');
            const rowChecks = document.querySelectorAll('.row-check');
            const batchBtn = document.getElementById('batch-btn');
            const batchCount = document.getElementById('batch-count');

            const refreshBatch = () => {
                const checked = document.querySelectorAll('.row-check:checked').length;
                batchCount.textContent = checked;
                batchBtn.classList.toggle('d-none', checked === 0);
                batchBtn.disabled = checked === 0;
            };

            checkAll?.addEventListener('change', () => {
                rowChecks.forEach(cb => cb.checked = checkAll.checked);
                refreshBatch();
            });
            rowChecks.forEach(cb => cb.addEventListener('change', refreshBatch));

            document.getElementById('batch-form')?.addEventListener('submit', (e) => {
                if (!confirm(e.target.dataset.confirm)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
