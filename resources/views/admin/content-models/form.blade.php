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
                <label class="inline-flex items-center mr-3"><input type="checkbox" name="is_commentable" id="is_commentable" value="1" checked> 允许评论</label>
            </div>
            <div class="form-group">
                <label for="sort">排序</label>
                <input type="number" class="form-control" id="sort" name="sort" value="0">
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.content-models.index') }}" class="btn btn-default">返回</a>
        </form>

        @if ($mode === 'edit')
        <hr>
        <h4>字段管理</h4>
        <p class="text-gray-500" style="font-size:12px">
            新增字段将同时向数据表 <code id="table-name-hint">data_{alias}</code> 添加物理列（列名 <code>field_{'{id}'}</code> 由系统生成）。
            select / radio 的选项在"额外配置"填 JSON，如 <code>{"options":["选项一","选项二"]}</code>。
        </p>
        <form id="field-form">
            <div class="flex flex-wrap gap-2 items-end">
                <div class="form-group flex-1">
                    <input type="text" class="form-control" id="f-field-name" placeholder="字段英文名（如 salary）" required>
                </div>
                <div class="form-group flex-1">
                    <input type="text" class="form-control" id="f-field-label" placeholder="显示标签" required>
                </div>
                <div class="form-group flex-1">
                    <select class="form-control" id="f-field-type">
                        <option value="text">单行文本</option>
                        <option value="rich_text">富文本</option>
                        <option value="number">数字</option>
                        <option value="integer">整数</option>
                        <option value="date">日期时间</option>
                        <option value="image">图片</option>
                        <option value="file">文件</option>
                        <option value="select">下拉单选</option>
                        <option value="radio">单选组</option>
                        <option value="checkbox">开关</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <input type="text" class="form-control" id="f-extra-config" placeholder='额外配置 JSON（可选）'>
                </div>
                <div class="form-group flex-1">
                    <label class="inline-flex items-center mr-3" style="padding-top:7px"><input type="checkbox" id="f-required"> 必填</label>
                </div>
                <div class="form-group flex-1">
                    <button type="submit" class="btn bg-primary-500 text-white w-full">添加</button>
                </div>
            </div>
        </form>
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>字段名</th><th width="100">物理列</th><th>标签</th><th width="90">类型</th><th width="60">必填</th><th width="60">排序</th><th width="80">操作</th></tr></thead>
            <tbody id="fields-tbody"><tr><td colspan="8" class="text-gray-500">加载中…</td></tr></tbody>
        </table>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};
    const pick = (o, snake) => o?.[snake] ?? o?.[snake.replace(/_([a-z])/g, (_, c) => c.toUpperCase())];

    // field_type → column_type 映射（与 ModelFieldService::addColumnToModelTable 的解析对应）
    const COLUMN_TYPE_MAP = {
        text: 'varchar(500)', rich_text: 'longtext', number: 'decimal(10,2)', integer: 'int',
        date: 'datetime', image: 'varchar(255)', file: 'varchar(255)', select: 'varchar(255)',
        radio: 'varchar(50)', checkbox: 'tinyint(1)', json: 'json',
    };

    if (id) {
        adminApi.get('/api/admin/contentModel/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['name', 'description', 'sort'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
            document.getElementById('alias').readOnly = true; // 表名固化，别名不可改
            const hint = document.getElementById('table-name-hint');
            if (hint && (pick(d, 'table_name'))) hint.textContent = pick(d, 'table_name');
            loadFields();
        });
    }

    function loadFields() {
        adminApi.post('/api/admin/modelField/search', {page: 1, pageSize: 200, model_id: id}).then(res => {
            const rows = adminApi.rows(res).sort((a, b) => (pick(a, 'sort_order') ?? 0) - (pick(b, 'sort_order') ?? 0));
            const tbody = document.getElementById('fields-tbody');
            tbody.innerHTML = rows.length ? rows.map(f => `<tr>
                <td>${f.id}</td>
                <td>${adminApi.esc(pick(f, 'field_name'))}</td>
                <td><code>${adminApi.esc(pick(f, 'column_name'))}</code></td>
                <td>${adminApi.esc(pick(f, 'field_label'))}</td>
                <td>${adminApi.esc(pick(f, 'field_type'))}</td>
                <td>${Number(pick(f, 'is_required') ?? 0) === 1 ? '是' : '否'}</td>
                <td>${pick(f, 'sort_order') ?? 0}</td>
                <td><button class="btn" onclick="destroyField(${f.id})">删除</button></td>
            </tr>`).join('') : '<tr><td colspan="8" class="text-gray-500">暂无字段</td></tr>';
        });
    }

    // 新增字段（column_name 留空由服务端按 field_{id} 补齐，并同步物理列）
    document.getElementById('field-form')?.addEventListener('submit', async e => {
        e.preventDefault();
        const fieldType = document.getElementById('f-field-type').value;
        const extra = document.getElementById('f-extra-config').value.trim();
        const payload = {
            modelId: id,
            fieldName: document.getElementById('f-field-name').value.trim(),
            fieldLabel: document.getElementById('f-field-label').value.trim(),
            fieldType,
            columnType: COLUMN_TYPE_MAP[fieldType] ?? 'varchar(255)',
            isRequired: document.getElementById('f-required').checked ? 1 : 0,
            isUnique: 0,
            sortOrder: 100 + document.querySelectorAll('#fields-tbody tr').length * 10,
        };
        if (extra) {
            try { JSON.parse(extra); } catch (err) { alert('额外配置不是合法 JSON'); return; }
            payload.extraConfig = extra;
        }
        const res = await adminApi.post('/api/admin/modelField/store', payload);
        if (res.code !== 0) { alert(res.message || '添加失败'); return; }
        document.getElementById('f-field-name').value = '';
        document.getElementById('f-field-label').value = '';
        document.getElementById('f-extra-config').value = '';
        loadFields();
    });

    function destroyField(fieldId) {
        if (!confirm('确认删除该字段？物理列不会被自动删除（如需清理请手动 ALTER TABLE）。')) return;
        adminApi.post('/api/admin/modelField/destroy', {id: fieldId}).then(loadFields);
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
