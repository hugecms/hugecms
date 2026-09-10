@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增内容' : '编辑内容')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增内容' : '编辑内容' }}</strong></div>
    <div class="panel-body">
        <form id="content-form">
            {{-- ========== 公共字段（contents 主表） ========== --}}
            <div class="form-group">
                <label for="model_id">内容模型</label>
                <select class="form-control" id="model_id"></select>
            </div>
            <div class="form-group">
                <label for="title">标题 <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" required>
            </div>
            <div class="form-group">
                <label for="slug">URL 别名</label>
                <input type="text" class="form-control" id="slug" placeholder="留空自动生成">
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select class="form-control" id="status">
                    <option value="draft">草稿</option>
                    <option value="published">发布</option>
                    <option value="pending">定时发布</option>
                    <option value="archived">归档</option>
                </select>
            </div>
            <div class="form-group">
                <label for="published_at">发布时间（定时发布时必填）</label>
                <input type="text" class="form-control" id="published_at" placeholder="YYYY-MM-DD HH:mm:ss">
            </div>
            <div class="form-group">
                <label for="visibility">可见性</label>
                <select class="form-control" id="visibility">
                    <option value="public">公开</option>
                    <option value="password">密码保护</option>
                    <option value="private">私密（仅登录可见）</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">访问密码（可见性为密码保护时填写）</label>
                <input type="text" class="form-control" id="password">
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" id="is_top"> 置顶（列表排序优先）</label>
            </div>
            <div class="form-group">
                <label for="sort">手动排序（越大越靠前）</label>
                <input type="number" class="form-control" id="sort" value="0">
            </div>

            <hr>
            <h4>模型字段（data_{alias} 动态表）</h4>
            <div id="dynamic-fields"><p class="text-muted">字段加载中…</p></div>

            <button type="submit" class="btn btn-primary">保存</button>
            <a href="{{ route('admin.contents.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};
    const ADMIN_ID = {{ auth()->id() ?? 'null' }};

    // 模型动态数据接口：alias=article → /api/admin/dataArticle（新建模型后需生成对应 API）
    const dataEndpoint = alias => '/api/admin/data' + alias.charAt(0).toUpperCase() + alias.slice(1);

    // 兼容下划线/驼峰两种返回形态
    const pick = (o, snake) => o?.[snake] ?? o?.[snake.replace(/_([a-z])/g, (_, c) => c.toUpperCase())];

    let currentModel = null; // {id, alias}
    let dynFields = [];      // model_fields 定义
    let dynRowId = null;     // 动态表已有行 ID（编辑）
    let dataRow = null;      // 动态表数据（编辑）

    // 1) 模型下拉
    adminApi.post('/api/admin/contentModel/search', {page: 1, pageSize: 100}).then(res => {
        const sel = document.getElementById('model_id');
        adminApi.rows(res).forEach(m => {
            sel.insertAdjacentHTML('beforeend',
                `<option value="${m.id}" data-alias="${adminApi.esc(m.alias)}">${adminApi.esc(m.name)}</option>`);
        });
        sel.addEventListener('change', () => loadFields());

        if (id) {
            // 编辑：先取内容确定模型与公共字段
            adminApi.get('/api/admin/content/show?id=' + id).then(res2 => {
                const d = res2.data ?? {};
                sel.value = pick(d, 'model_id');
                ['title', 'slug', 'status', 'visibility', 'password', 'published_at', 'sort'].forEach(k => {
                    const el = document.getElementById(k);
                    const v = pick(d, k);
                    if (el && v !== undefined && v !== null) el.value = v;
                });
                document.getElementById('is_top').checked = Number(pick(d, 'is_top') ?? 0) === 1;
                loadFields();
            });
        } else {
            loadFields();
        }
    });

    // 2) 按模型渲染动态字段控件
    async function loadFields() {
        const sel = document.getElementById('model_id');
        currentModel = {id: Number(sel.value), alias: sel.selectedOptions[0]?.dataset.alias ?? ''};
        dynRowId = null;
        dataRow = null;

        const res = await adminApi.post('/api/admin/modelField/search', {page: 1, pageSize: 100, model_id: currentModel.id});
        dynFields = adminApi.rows(res).sort((a, b) => (pick(a, 'sort_order') ?? 0) - (pick(b, 'sort_order') ?? 0));
        renderFields();
        if (id) await fillDynamic();
    }

    function renderFields() {
        const box = document.getElementById('dynamic-fields');
        if (!dynFields.length) { box.innerHTML = '<p class="text-muted">该模型未定义字段</p>'; return; }
        box.innerHTML = dynFields.map(f => {
            const type = pick(f, 'field_type');
            const col = pick(f, 'column_name');
            const label = adminApi.esc(pick(f, 'field_label'));
            const required = Number(pick(f, 'is_required') ?? 0) === 1;
            const star = required ? ' <span class="text-danger">*</span>' : '';
            const reqAttr = required ? 'required' : '';

            let control;
            if (type === 'rich_text') {
                control = `<textarea class="form-control" data-dyn data-type="${type}" id="${col}" rows="12" ${reqAttr}></textarea>`;
            } else if (type === 'json') {
                control = `<textarea class="form-control" data-dyn data-type="${type}" id="${col}" rows="4" placeholder="JSON"></textarea>`;
            } else if (type === 'select' || type === 'radio') {
                let opts = [];
                try { opts = JSON.parse(pick(f, 'extra_config') ?? '{}').options ?? []; } catch (e) {}
                if (type === 'select') {
                    control = `<select class="form-control" data-dyn data-type="${type}" id="${col}">
                        <option value="">请选择</option>
                        ${opts.map(o => `<option value="${adminApi.esc(o)}">${adminApi.esc(o)}</option>`).join('')}
                    </select>`;
                } else {
                    control = opts.map(o => `<label class="radio-inline" style="margin-right:14px">
                        <input type="radio" data-dyn data-type="${type}" name="${col}" value="${adminApi.esc(o)}"> ${adminApi.esc(o)}
                    </label>`).join('');
                }
            } else if (type === 'checkbox') {
                control = `<label class="checkbox-inline"><input type="checkbox" data-dyn data-type="${type}" id="${col}" value="1"></label>`;
            } else if (type === 'number' || type === 'integer') {
                control = `<input type="number" step="${type === 'integer' ? '1' : 'any'}" class="form-control" data-dyn data-type="${type}" id="${col}">`;
            } else if (type === 'date') {
                control = `<input type="text" class="form-control" data-dyn data-type="${type}" id="${col}" placeholder="YYYY-MM-DD HH:mm:ss">`;
            } else if (type === 'image' || type === 'file') {
                control = `<input type="text" class="form-control" data-dyn data-type="${type}" id="${col}" placeholder="附件ID或URL（上传对接开发中）">`;
            } else {
                control = `<input type="text" class="form-control" data-dyn data-type="${type}" id="${col}">`;
            }
            return `<div class="form-group"><label for="${col}">${label}${star}</label>${control}</div>`;
        }).join('');
    }

    // 3) 编辑回填动态数据
    async function fillDynamic() {
        if (!currentModel?.alias || !id) return;
        const res = await adminApi.post(dataEndpoint(currentModel.alias) + '/search', {page: 1, pageSize: 1, content_id: id});
        dataRow = adminApi.rows(res)[0];
        if (!dataRow) return;
        dynRowId = dataRow.id;

        dynFields.forEach(f => {
            const col = pick(f, 'column_name');
            const type = pick(f, 'field_type');
            const v = pick(dataRow, col);
            if (v === undefined || v === null) return;
            if (type === 'radio') {
                const r = document.querySelector(`input[name="${col}"][value="${String(v).replace(/"/g, '&quot;')}"]`);
                if (r) r.checked = true;
            } else if (type === 'checkbox') {
                document.getElementById(col).checked = Number(v) === 1;
            } else {
                const el = document.getElementById(col);
                if (el) el.value = typeof v === 'object' ? JSON.stringify(v) : v;
            }
        });
    }

    // 4) 保存：先 contents 主表，再 data_{alias} 动态表
    document.getElementById('content-form').addEventListener('submit', async e => {
        e.preventDefault();
        if (!currentModel) return;

        const visibility = document.getElementById('visibility').value;
        const common = {
            modelId: currentModel.id,
            title: document.getElementById('title').value.trim(),
            slug: document.getElementById('slug').value.trim() || ('post-' + Date.now()), // TODO 后端按标题拼音生成
            authorId: ADMIN_ID,
            status: document.getElementById('status').value,
            visibility,
            password: visibility === 'password' ? document.getElementById('password').value : '',
            sort: Number(document.getElementById('sort').value || 0),
            isTop: document.getElementById('is_top').checked ? 1 : 0,
            publishedAt: document.getElementById('published_at').value || null,
            auditStatus: 'approved', // 有发布权限角色保存即过审（约定文档第二节状态机）
        };

        const res = id
            ? await adminApi.put('/api/admin/content/update', {id, ...common})
            : await adminApi.post('/api/admin/content/store', common);
        if (res.code !== 0) { alert(res.message || '内容保存失败'); return; }

        let contentId = id ?? res.data?.id ?? 0;
        if (!contentId) {
            // 兜底：store 未返回 ID 时按标题检索最新一条
            const sres = await adminApi.post('/api/admin/content/search', {page: 1, pageSize: 1});
            contentId = adminApi.rows(sres)[0]?.id ?? 0;
        }
        if (!contentId) { alert('未获取内容ID，模型数据未保存'); return; }

        // 动态字段：column_name（field_1）→ API 驼峰（field1）
        const data = {contentId};
        dynFields.forEach(f => {
            const col = pick(f, 'column_name');
            const type = pick(f, 'field_type');
            const key = col.replace(/_([a-z])/g, (_, c) => c.toUpperCase());
            if (type === 'radio') {
                const r = document.querySelector(`input[name="${col}"]:checked`);
                data[key] = r ? r.value : '';
            } else if (type === 'checkbox') {
                data[key] = document.getElementById(col).checked ? 1 : 0;
            } else {
                data[key] = document.getElementById(col).value;
            }
        });

        const dres = dynRowId
            ? await adminApi.put(dataEndpoint(currentModel.alias) + '/update', {id: dynRowId, ...data})
            : await adminApi.post(dataEndpoint(currentModel.alias) + '/store', data);
        if (dres.code !== 0) { alert('内容已保存，但模型字段保存失败：' + (dres.message || '')); return; }

        location.href = "{{ route('admin.contents.index') }}";
    });
</script>
@endpush
