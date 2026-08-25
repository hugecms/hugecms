@extends('portal::layout')

@section('title', '忘记密码 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-3 text-center">忘记密码</h1>
                <p class="text-muted small">输入注册邮箱，我们将向你发送密码重置链接。</p>
                <form method="POST" action="/forgot-password">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">邮箱</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">发送重置链接</button>
                </form>

                <p class="text-center small mt-3 mb-0">
                    想起来了？<a href="{{ route('login') }}">直接登录</a>
                </p>
            </div>
        </div>
    </div>
@endsection
