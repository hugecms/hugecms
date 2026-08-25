@extends('portal::layout')

@section('title', '注册 - HugeCMS')

@section('content')
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">注册账号</h1>
                <form method="POST" action="/register">
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
                        <div class="input-group" id="password-toggle">
                            <input type="password" id="password" name="password" ref="passwordInput"
                                   class="form-control @error('password') is-invalid @enderror" required>
                            <button type="button" ref="toggleBtn" @click="togglePassword"
                                    class="btn btn-outline-secondary">显示</button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">确认密码</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">注册</button>
                </form>

                <p class="text-center small mt-3 mb-0">
                    已有账号？<a href="{{ route('login') }}">直接登录</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const { createApp, ref } = Vue;
        createApp({
            setup() {
                const passwordInput = ref(null);
                const toggleBtn = ref(null);
                const togglePassword = () => {
                    const input = passwordInput.value;
                    input.type = input.type === 'password' ? 'text' : 'password';
                    toggleBtn.value.textContent = input.type === 'password' ? '显示' : '隐藏';
                };
                return { passwordInput, toggleBtn, togglePassword };
            },
        }).mount('#password-toggle');
    </script>
@endsection
