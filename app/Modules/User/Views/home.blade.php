@extends('user::layout')

@section('title', '我的主页 - 用户中心')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h1 class="h4 mb-2">我的主页</h1>
            <p class="text-muted mb-0">你好，{{ auth()->user()->name }}！欢迎来到用户中心。</p>
        </div>
    </div>
@endsection
