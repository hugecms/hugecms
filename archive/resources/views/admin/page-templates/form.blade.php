@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增模板' : '编辑模板')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增模板' : '编辑模板' }}</strong></div>
    <div class="panel-body">
        <form id="template-form">
            <div class="form-group">
                <label for="template_name">模板名称</label>
                <input type="text" class="form-control" id="template_name" name="template_name" required>
            </div>
            <div class="form-group">
                <label for="template_code">模板代码（唯一标识）</label>
                <input type="text" class="form-control" id="template_code" name="template_code" required>
            </div>
            <div class="form-group">
                <label for="category">类别</label>
                <select class="form-control" id="category" name="category">
                    <option value="page">页面</option>
                    <option value="post">文章</option>
                    <option value="term">分类</option>
                </select>
            </div>
            <div class="form-group">
                <label for="content">模板内容（HTML/JSON 结构）</label>
                <textarea class="form-control" id="content" name="content" rows="10"></textarea>
            </div>
            <div class="form-group">
                <label class="inline-flex items-center mr-3"><input type="checkbox" name="is_default" id="is_default" value="1"> 设为该类别默认模板</label>
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.page-templates.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/pageTemplate/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['template_name', 'template_code', 'category', 'content'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
        });
    }

    document.getElementById('template-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/pageTemplate/update', {id, ...data})
            : adminApi.post('/api/admin/pageTemplate/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.page-templates.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
