@use('App\Models\User')
@extends('admin::layout')

@section('title', '仪表盘 - 管理后台')

@section('page-title', '仪表盘')

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="admin-stat-label mb-1">用户总数</p>
                    <p class="admin-stat-value mb-0">{{ User::count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="admin-stat-label mb-1">文章总数</p>
                    <p class="admin-stat-value mb-0">0</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="admin-stat-label mb-1">今日访问</p>
                    <p class="admin-stat-value mb-0">0</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <p class="admin-stat-label mb-1">待办事项</p>
                    <p class="admin-stat-value mb-0">0</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h2 class="h6 mb-2">欢迎使用 HugeCMS 管理后台</h2>
            <p class="text-muted small mb-0">从这里开始管理你的内容，更多功能模块正在建设中。</p>
        </div>
    </div>
@endsection
