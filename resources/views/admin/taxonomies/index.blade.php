@extends('admin.layouts.admin')

@section('title', '分类标签')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <div class="pull-right">
            <a href="{{ route('admin.taxonomies.create') }}" class="btn btn-primary btn-sm">新增分类法</a>
        </div>
        <strong>分类法</strong>
    </div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>名称</th><th width="120">别名</th><th width="90">层级</th><th width="130">操作</th></tr></thead>
            <tbody id="tbody"><tr><td colspan="5" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>

<div class="panel">
    <div class="panel-heading"><strong>分类项 / 标签</strong></div>
    <div class="panel-body">
        <form id="term-form" class="form-inline" style="margin-bottom:12px">
            <div class="form-group">
                <select class="form-control" id="term-taxonomy" style="width:140px" required></select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="term-name" placeholder="名称（如：科技）" required style="width:160px">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="term-slug" placeholder="别名（留空自动生成）" style="width:180px">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">新增分类项</button>
        </form>
        <table class="table table-hover">
            <thead><tr><th width="60">ID</th><th>名称</th><th width="140">别名</th><th width="110">所属分类法</th><th width="90">内容数</th><th width="130">操作</th></tr></thead>
            <tbody id="terms-tbody"><tr><td colspan="6" class="text-muted">加载中…</td></tr></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const base = "{{ route('admin.taxonomies.index') }}";
    const taxonomies = []; // [{id, name}]

    // 分类法列表
    adminApi.post('/api/admin/taxonomy/search', {page: 1, pageSize: 50}).then(res => {
        const rows = adminApi.rows(res);
        const tbody = document.getElementById('tbody');
        const sel = document.getElementById('term-taxonomy');
        if (!rows.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-muted">暂无数据</td></tr>';
            return;
        }
        tbody.innerHTML = rows.map(r => {
            taxonomies.push({id: r.id, name: r.name});
            sel.insertAdjacentHTML('beforeend', `<option value="${r.id}">${adminApi.esc(r.name)}</option>`);
            return `<tr>
                <td>${r.id}</td>
                <td>${adminApi.esc(r.name)}</td>
                <td>${adminApi.esc(r.alias)}</td>
                <td>${(r.isHierarchical ?? r.is_hierarchical) ? '层级' : '平铺'}</td>
                <td>
                    <a class="btn btn-sm" href="${base}/${r.id}/edit">编辑</a>
                    <button class="btn btn-sm" onclick="destroyTaxonomy(${r.id})">删除</button>
                </td>
            </tr>`;
        }).join('');
        loadTerms();
    });

    function destroyTaxonomy(id) {
        if (!confirm('确认删除该分类法？其下分类项与内容关联将一并删除。')) return;
        adminApi.post('/api/admin/taxonomy/destroy', {id}).then(() => location.reload());
    }

    // 分类项列表
    function loadTerms() {
        adminApi.post('/api/admin/term/search', {page: 1, pageSize: 100}).then(res => {
            const rows = adminApi.rows(res);
            const tbody = document.getElementById('terms-tbody');
            if (!rows.length) { tbody.innerHTML = '<tr><td colspan="6" class="text-muted">暂无数据</td></tr>'; return; }
            tbody.innerHTML = rows.map(r => {
                const tax = taxonomies.find(t => t.id === (r.taxonomyId ?? r.taxonomy_id));
                return `<tr>
                    <td>${r.id}</td>
                    <td>${adminApi.esc(r.name)}</td>
                    <td>${adminApi.esc(r.slug)}</td>
                    <td>${adminApi.esc(tax ? tax.name : (r.taxonomyId ?? r.taxonomy_id))}</td>
                    <td>${r.contentCount ?? r.content_count ?? 0}</td>
                    <td>
                        <button class="btn btn-sm" onclick="renameTerm(${r.id}, '${adminApi.esc(r.name).replace(/'/g, '')}')">重命名</button>
                        <button class="btn btn-sm" onclick="destroyTerm(${r.id})">删除</button>
                    </td>
                </tr>`;
            }).join('');
        });
    }

    // 新增分类项
    document.getElementById('term-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = {
            taxonomy_id: Number(document.getElementById('term-taxonomy').value),
            name: document.getElementById('term-name').value.trim(),
            slug: document.getElementById('term-slug').value.trim(),
            parent_id: 0,
            sort: 0,
        };
        if (!data.slug) delete data.slug; // 留空由后端按名称生成
        adminApi.post('/api/admin/term/store', data).then(res => {
            if (res.code === 0) { loadTerms(); document.getElementById('term-name').value = ''; document.getElementById('term-slug').value = ''; }
            else alert(res.message || '新增失败');
        });
    });

    function renameTerm(id, current) {
        const name = prompt('新名称：', current);
        if (!name || name === current) return;
        adminApi.put('/api/admin/term/update', {id, name}).then(() => loadTerms());
    }

    function destroyTerm(id) {
        if (!confirm('确认删除该分类项？内容的分类关联将一并移除。')) return;
        adminApi.post('/api/admin/term/destroy', {id}).then(() => loadTerms());
    }
</script>
@endpush
