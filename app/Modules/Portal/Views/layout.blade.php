<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HugeCMS')</title>
    <link href="{{ asset('static/bootstrap-5.3.8/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/css/hugecms.css') }}" rel="stylesheet" />
</head>
<body class="bg-body-secondary">
@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="关闭"></button>
    </div>
@endif
@yield('content')
<script src="{{ asset('static/jquery-4.0.0/jquery.min.js') }}"></script>
<script src="{{ asset('static/bootstrap-5.3.8/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('static/vue-3.5.41/vue.global.prod.js') }}"></script>
@yield('scripts')
</body>
</html>
