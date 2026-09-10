@extends('admin.layouts.admin')

@section('title', $mode === 'create' ? '新增区块' : '编辑区块')

@section('content')
<div class="panel">
    <div class="panel-heading"><strong>{{ $mode === 'create' ? '新增区块' : '编辑区块' }}</strong></div>
    <div class="panel-body">
        <form id="block-form">
            <div class="form-group">
                <label for="block_name">区块名称</label>
                <input type="text" class="form-control" id="block_name" name="block_name" required>
            </div>
            <div class="form-group">
                <label for="block_type">区块类型</label>
                <select class="form-control" id="block_type" name="block_type">
                    <option value="header">页头</option>
                    <option value="footer">页尾</option>
                    <option value="banner">横幅</option>
                    <option value="content">内容</option>
                    <option value="sidebar">侧栏</option>
                    <option value="custom">自定义</option>
                </select>
            </div>
            <div class="form-group">
                <label for="content">区块内容（HTML/JSON）</label>
                <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
            </div>
            <div class="form-group">
                <label for="css">自定义 CSS</label>
                <textarea class="form-control" id="css" name="css" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="js">自定义 JS</label>
                <textarea class="form-control" id="js" name="js" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label class="inline-flex items-center mr-3"><input type="checkbox" name="is_global" id="is_global" value="1"> 全局区块（全站复用）</label>
            </div>
            <button type="submit" class="btn bg-primary-500 text-white">保存</button>
            <a href="{{ route('admin.blocks.index') }}" class="btn btn-default">返回</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const id = {{ $id ?? 'null' }};

    if (id) {
        adminApi.get('/api/admin/block/show?id=' + id).then(res => {
            const d = res.data ?? {};
            ['block_name', 'block_type', 'content', 'css', 'js'].forEach(k => {
                const el = document.getElementById(k);
                if (el && d[k] !== undefined) el.value = d[k];
            });
        });
    }

    document.getElementById('block-form').addEventListener('submit', e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        const req = id
            ? adminApi.put('/api/admin/block/update', {id, ...data})
            : adminApi.post('/api/admin/block/store', data);
        req.then(res => {
            if (res.code === 0) location.href = "{{ route('admin.blocks.index') }}";
            else alert(res.message || '保存失败');
        });
    });
</script>
@endpush
