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
                <label class="inline-flex items-center mr-3"><input type="checkbox" id="is_top"> 置顶（列表排序优先）</label>
            </div>
            <div class="form-group">
                <label for="sort">手动排序（越大越靠前）</label>
                <input type="number" class="form-control" id="sort" value="0">
            </div>

            <hr>
            <h4>模型字段（data_{alias} 动态表）</h4>
            <div id="dynamic-fields"><p class="text-gray-500">字段加载中…</p></div>

            <hr>
            <h4>分类与标签</h4>
            <div id="taxonomy-section"><p class="text-gray-500">加载中…</p></div>

            <hr>
            <h4>SEO 设置（seo_meta）</h4>
            <div class="form-group">
                <label for="seo_title">SEO 标题（浏览器 Tab 显示）</label>
                <input type="text" class="form-control" id="seo_title">
            </div>
            <div class="form-group">
                <label for="seo_keywords">关键词（逗号分隔）</label>
                <input type="text" class="form-control" id="seo_keywords">
            </div>
            <div class="form-group">
                <label for="seo_description">描述（搜索结果展示）</label>
                <textarea class="form-control" id="seo_description" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="seo_canonical">权威链接 canonical（防重复页，留空默认）</label>
                <input type="text" class="form-control" id="seo_canonical">
            </div>
            <div class="form-group">
                <label for="seo_robots">robots 策略</label>
                <input type="text" class="form-control" id="seo_robots" placeholder="index,follow">
            </div>

            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
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

    let currentModel = null;   // {id, alias}
    let dynFields = [];        // model_fields 定义
    let dynRowId = null;       // 动态表已有行 ID（编辑）
    let termRelations = [];    // 已有关联绑定 [{id, termId}]（编辑）
    let seoRowId = null;       // 已有 SEO 记录 ID（编辑）

    // ========== 1) 模型下拉与公共字段 ==========
    adminApi.post('/api/admin/contentModel/search', {page: 1, pageSize: 100}).then(res => {
        const sel = document.getElementById('model_id');
        adminApi.rows(res).forEach(m => {
            sel.insertAdjacentHTML('beforeend',
                `<option value="${m.id}" data-alias="${adminApi.esc(m.alias)}">${adminApi.esc(m.name)}</option>`);
        });
        sel.addEventListener('change', () => { loadFields(); loadTaxonomies(); });

        if (id) {
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
                loadTaxonomies();
                loadSeo();
            });
        } else {
            loadFields();
            loadTaxonomies();
        }
    });

    // ========== 2) 动态字段 ==========
    async function loadFields() {
        const sel = document.getElementById('model_id');
        currentModel = {id: Number(sel.value), alias: sel.selectedOptions[0]?.dataset.alias ?? ''};
        dynRowId = null;
        const res = await adminApi.post('/api/admin/modelField/search', {page: 1, pageSize: 100, model_id: currentModel.id});
        dynFields = adminApi.rows(res).sort((a, b) => (pick(a, 'sort_order') ?? 0) - (pick(b, 'sort_order') ?? 0));
        renderFields();
        if (id) await fillDynamic();
    }

    function renderFields() {
        const box = document.getElementById('dynamic-fields');
        if (!dynFields.length) { box.innerHTML = '<p class="text-gray-500">该模型未定义字段</p>'; return; }
        box.innerHTML = dynFields.map(f => {
            const type = pick(f, 'field_type');
            const col = pick(f, 'column_name');
            const label = adminApi.esc(pick(f, 'field_label'));
            const required = Number(pick(f, 'is_required') ?? 0) === 1;
            const star = required ? ' <span class="text-danger">*</span>' : '';
            const reqAttr = required ? 'required' : '';

            let control;
            if (type === 'rich_text') {
                control = `<textarea class="form-control" id="${col}" rows="12" ${reqAttr}></textarea>`;
            } else if (type === 'json') {
                control = `<textarea class="form-control" id="${col}" rows="4" placeholder="JSON"></textarea>`;
            } else if (type === 'select' || type === 'radio') {
                let opts = [];
                try { opts = JSON.parse(pick(f, 'extra_config') ?? '{}').options ?? []; } catch (e) {}
                if (type === 'select') {
                    control = `<select class="form-control" id="${col}"><option value="">请选择</option>${opts.map(o => `<option value="${adminApi.esc(o)}">${adminApi.esc(o)}</option>`).join('')}</select>`;
                } else {
                    control = opts.map(o => `<label class="inline-flex items-center mr-3" style="margin-right:14px"><input type="radio" name="${col}" value="${adminApi.esc(o)}"> ${adminApi.esc(o)}</label>`).join('');
                }
            } else if (type === 'checkbox') {
                control = `<label class="inline-flex items-center mr-3"><input type="checkbox" id="${col}" value="1"></label>`;
            } else if (type === 'number' || type === 'integer') {
                control = `<input type="number" step="${type === 'integer' ? '1' : 'any'}" class="form-control" id="${col}">`;
            } else if (type === 'date') {
                control = `<input type="text" class="form-control" id="${col}" placeholder="YYYY-MM-DD HH:mm:ss">`;
            } else if (type === 'image' || type === 'file') {
                control = `<input type="text" class="form-control" id="${col}" placeholder="附件ID或URL（上传对接开发中）">`;
            } else {
                control = `<input type="text" class="form-control" id="${col}">`;
            }
            return `<div class="form-group"><label for="${col}">${label}${star}</label>${control}</div>`;
        }).join('');
    }

    async function fillDynamic() {
        if (!currentModel?.alias || !id) return;
        const res = await adminApi.post(dataEndpoint(currentModel.alias) + '/search', {page: 1, pageSize: 1, content_id: id});
        const row = adminApi.rows(res)[0];
        if (!row) return;
        dynRowId = row.id;
        dynFields.forEach(f => {
            const col = pick(f, 'column_name');
            const type = pick(f, 'field_type');
            const v = pick(row, col);
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

    // ========== 3) 分类与标签（按模型绑定的分类法渲染勾选） ==========
    async function loadTaxonomies() {
        if (!currentModel) return;
        termRelations = [];
        const box = document.getElementById('taxonomy-section');
        const taxRes = await adminApi.post('/api/admin/taxonomy/search', {page: 1, pageSize: 50, model_id: currentModel.id});
        const taxonomies = adminApi.rows(taxRes);
        if (!taxonomies.length) { box.innerHTML = '<p class="text-gray-500">该模型未绑定分类法</p>'; return; }

        // term 检索不支持 taxonomy_id 条件，全量拉取后前端分组
        const termRes = await adminApi.post('/api/admin/term/search', {page: 1, pageSize: 500});
        const allTerms = adminApi.rows(termRes);

        box.innerHTML = taxonomies.map(t => {
            const hierarchical = Number(pick(t, 'is_hierarchical') ?? 1) === 1;
            const terms = allTerms.filter(x => Number(pick(x, 'taxonomy_id')) === Number(t.id));
            const items = terms.map(x => {
                const depth = hierarchical ? countDepth(x, allTerms) : 0;
                return `<label class="${hierarchical ? 'inline-flex items-center mr-3' : 'inline-flex items-center mr-3'}" style="margin:0 16px 6px 0;${hierarchical && depth ? 'padding-left:' + (depth * 20) + 'px' : ''}">
                    <input type="checkbox" class="term-check" value="${x.id}"> ${adminApi.esc(x.name)}
                </label>`;
            }).join('');
            return `<div style="margin-bottom:8px"><strong>${adminApi.esc(t.name)}</strong>${hierarchical ? '' : ' <span class="text-gray-500">（标签）</span>'}<div style="margin-top:4px">${items || '<span class="text-gray-500">暂无分类项</span>'}</div></div>`;
        }).join('');

        if (id) await fillTermRelations(allTerms);
    }

    function countDepth(term, all) {
        let depth = 0;
        let cur = term;
        while (Number(pick(cur, 'parent_id') ?? 0) > 0) {
            const parent = all.find(x => Number(x.id) === Number(pick(cur, 'parent_id')));
            if (!parent) break;
            cur = parent;
            depth++;
            if (depth > 5) break;
        }
        return depth;
    }

    // 关联回显：termRelationship 检索不支持 content_id，按 term_id 反查后过滤
    async function fillTermRelations(allTerms) {
        await Promise.all(allTerms.map(async t => {
            const res = await adminApi.post('/api/admin/termRelationship/search', {page: 1, pageSize: 500, term_id: t.id});
            adminApi.rows(res).forEach(r => {
                if (Number(pick(r, 'content_id')) === Number(id)) {
                    termRelations.push({id: r.id, termId: t.id});
                    const el = document.querySelector(`.term-check[value="${t.id}"]`);
                    if (el) el.checked = true;
                }
            });
        }));
    }

    // ========== 4) SEO ==========
    async function loadSeo() {
        if (!id) return;
        const res = await adminApi.post('/api/admin/seoMeta/search', {page: 1, pageSize: 20, target_id: id});
        const row = adminApi.rows(res).find(r => (pick(r, 'target_type') ?? '') === 'content');
        if (!row) return;
        seoRowId = row.id;
        const map = {seo_title: 'title', seo_keywords: 'keywords', seo_description: 'description', seo_canonical: 'canonical_url', seo_robots: 'robots'};
        Object.entries(map).forEach(([elId, key]) => {
            const v = pick(row, key);
            if (v !== undefined && v !== null) document.getElementById(elId).value = v;
        });
    }

    // ========== 5) 保存：contents → data_{alias} → 分类关联 → SEO ==========
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
            const sres = await adminApi.post('/api/admin/content/search', {page: 1, pageSize: 1});
            contentId = adminApi.rows(sres)[0]?.id ?? 0;
        }
        if (!contentId) { alert('未获取内容ID，后续数据未保存'); return; }

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

        // 分类关联差异同步
        const checked = [...document.querySelectorAll('.term-check:checked')].map(i => Number(i.value));
        const bound = termRelations.map(r => Number(r.termId));
        for (const r of termRelations) {
            if (!checked.includes(Number(r.termId))) {
                await adminApi.post('/api/admin/termRelationship/destroy', {id: r.id});
            }
        }
        for (const termId of checked) {
            if (!bound.includes(termId)) {
                await adminApi.post('/api/admin/termRelationship/store', {contentId, termId, sort: 0});
            }
        }

        // SEO（有记录则更新；无记录且填了内容则新建）
        const seo = {
            targetType: 'content',
            targetId: contentId,
            title: document.getElementById('seo_title').value,
            keywords: document.getElementById('seo_keywords').value,
            description: document.getElementById('seo_description').value,
            canonicalUrl: document.getElementById('seo_canonical').value,
            robots: document.getElementById('seo_robots').value || 'index,follow',
        };
        const hasSeo = Object.values(seo).some(v => v && v !== 'content' && v !== 'index,follow' && v !== contentId);
        if (seoRowId) {
            await adminApi.put('/api/admin/seoMeta/update', {id: seoRowId, ...seo});
        } else if (hasSeo) {
            await adminApi.post('/api/admin/seoMeta/store', seo);
        }

        location.href = "{{ route('admin.contents.index') }}";
    });
</script>
@endpush
