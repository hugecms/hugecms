@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增表单' : '编辑表单')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增表单' : '编辑表单' }}</strong></div>
    <div class="panel-body">
        <form id="form-form">
            <div class="form-group">
                <label for="name">表单名称</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="alias">表单标识（用于代码调用）</label>
                <input type="text" class="form-control" id="alias" name="alias" required>
            </div>
            <div class="form-group">
                <label for="fields_config">字段配置（JSON 数组：字段名、类型、校验规则、选项）</label>
                <textarea class="form-control" id="fields_config" name="fields_config" rows="10" required>[{"name":"name","label":"姓名","type":"text","required":true},{"name":"contact","label":"联系方式","type":"text","required":true},{"name":"message","label":"留言内容","type":"textarea"}]</textarea>
            </div>
            <div class="form-group">
                <label for="success_message">提交成功提示语</label>
                <input type="text" class="form-control" id="success_message" name="success_message" value="提交成功！">
            </div>
            <div class="form-group">
                <label class="inline-flex items-center mr-3"><input type="checkbox" name="is_active" id="is_active" value="1" checked> 启用</label>
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.forms.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/formTemplate/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'alias', 'success_message'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            const cfg = document.getElementById('fields_config');
            const raw = d.fieldsConfig ?? d.fields_config;
            if (raw && cfg) cfg.value = typeof raw === 'string' ? raw : JSON.stringify(raw, null, 2);
        });
    }

    document.getElementById('form-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        try { JSON.parse(data.fields_config); } catch (e) { alert('字段配置不是合法 JSON'); return; }
        const req = id
            ? adminApi.put('/api/admin/formTemplate/update', {id, ...data})
            : adminApi.post('/api/admin/formTemplate/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.forms.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
