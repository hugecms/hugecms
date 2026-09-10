@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增模型' : '编辑模型')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增模型' : '编辑模型' }}</strong></div>
    <div class="panel-body">
        <form id="model-form">
            <div class="form-group">
                <label for="name">模型名称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="alias">别名（小写字母/数字/下划线，用于 URL 与数据表 data_{alias}，创建后不可改）</label>
                <input type="text" class="form-control" id="alias" name="alias" pattern="[a-z][a-z0-9_]{0,39}" required>
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" name="is_commentable" id="is_commentable" value="1" checked> 允许评论</label>
            </div>
            <div class="form-group">
                <label for="sort">排序</label>
                <input type="number" class="form-control" id="sort" name="sort" value="0">
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.content-models.index') }}" class="btn btn-default">返回</a>
        </form>

        @if ($mode === 'edit')
        <hr>
        <h4>字段管理</h4>
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>字段名</th><th>标签</th><th width="90">类型</th><th width="80">必填</th><th width="70">排序</th></tr></thead>
            <tbody id="fields-tbody"><tr><td colspan="6" class="text-muted">加载中…</td></tr></tbody>
        </table>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/contentModel/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'description', 'sort'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            document.getElementById('alias').readOnly = true; // 表名固化，别名不可改

            adminApi.post('/api/admin/modelField/search', {page: 1, pageSize: 100, model_id: id}).then(res2 => {
                const rows = adminApi.rows(res2);
                const tbody = document.getElementById('fields-tbody');
                tbody.innerHTML = rows.length ? rows.map(f => `<tr>
                    <td>${f.id}</td>
                    <td>${adminApi.esc(f.fieldName ?? f.field_name)}</td>
                    <td>${adminApi.esc(f.fieldLabel ?? f.field_label)}</td>
                    <td>${adminApi.esc(f.fieldType ?? f.field_type)}</td>
                    <td>${(f.isRequired ?? f.is_required) ? '是' : '否'}</td>
                    <td>${f.sortOrder ?? f.sort_order ?? 0}</td>
                </tr>`).join('') : '<tr><td colspan="6" class="text-muted">暂无字段</td></tr>';
            });
        });
    }

    document.getElementById('model-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/contentModel/update', {id, ...data})
            : adminApi.post('/api/admin/contentModel/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.content-models.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
