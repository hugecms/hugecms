@extends('admin::layout')

@section('title', '新建用户 - 管理后台')
@section('page-title', '新建用户')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h5 mb-1">新建用户</h1>
            <p class="text-muted small mb-0">创建一个新的系统用户</p>
        </div>
        <a href="{{ route('admin.user') }}" class="btn btn-outline-secondary btn-sm">返回列表</a>
    </div>

    <div class="card" style="max-width: 640px;">
        <div class="card-body">
            <form method="POST" action="/admin/user">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">用户名</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">邮箱</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">密码</label>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">确认密码</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control @error('password') is-invalid @enderror" required>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">状态</label>
                    <div class="d-flex gap-3">
                        @foreach ($statusOptions as $option)
                            <div class="form-check">
                                <input type="radio" id="status-{{ $option['value'] }}" name="status"
                                       value="{{ $option['value'] }}"
                                       class="form-check-input @error('status') is-invalid @enderror"
                                       {{ old('status', (string) $option['value']) === (string) $option['value'] ? 'checked' : '' }}>
                                <label for="status-{{ $option['value'] }}" class="form-check-label">{{ $option['label'] }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">创建</button>
                    <a href="{{ route('admin.user') }}" class="btn btn-outline-secondary btn-sm">取消</a>
                </div>
            </form>
        </div>
    </div>
@endsection
