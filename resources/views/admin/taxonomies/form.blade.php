@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增分类法' : '编辑分类法')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增分类法' : '编辑分类法' }}</strong></div>
    <div class="panel-body">
        <form id="taxonomy-form">
            <div class="form-group">
                <label for="name">分类法名称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="alias">别名（URL 用，创建后不可改）</label>
                <input type="text" class="form-control" id="alias" name="alias" pattern="[a-z][a-z0-9_]{0,39}" required>
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" name="is_hierarchical" id="is_hierarchical" value="1" checked> 支持层级（分类目录；不勾选则为标签）</label>
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.taxonomies.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/taxonomy/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'description'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            document.getElementById('alias').readOnly = true;
        });
    }

    document.getElementById('taxonomy-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/taxonomy/update', {id, ...data})
            : adminApi.post('/api/admin/taxonomy/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.taxonomies.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
