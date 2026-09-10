@extends('admin.layouts.admin')

@section('title', '系统设置')

@section('content')
<div class="panel">
    <div class="panel-heading">
        <ul class="nav nav-tabs" id="option-tabs" style="margin-bottom:10px">
            <li class="active"><a href="#tab-general">常规</a></li>
            <li><a href="#tab-comment">评论</a></li>
            <li><a href="#tab-permalink">固定链接</a></li>
            <li><a href="#tab-storage">存储</a></li>
            <li><a href="#tab-mail">邮件</a></li>
        </ul>
    </div>
    <div class="panel-body tab-content">
        <div class="tab-pane active" id="tab-general">
            <div class="form-group">
                <label>站点名称（sites 表）</label>
                <input type="text" class="form-control" id="site_name">
            </div>
            <div class="form-group">
                <label>站点标语 / ICP 备案号（options.site_info）</label>
                <input type="text" class="form-control" id="site_info">
            </div>
        </div>
        <div class="tab-pane" id="tab-comment">
            <div class="form-group">
                <label>评论配置（options.comment_config：先审后发/游客评论/必填项）</label>
                <textarea class="form-control" id="comment_config" rows="6"></textarea>
            </div>
        </div>
        <div class="tab-pane" id="tab-permalink">
            <div class="form-group">
                <label>固定链接规则（options.permalink）</label>
                <textarea class="form-control" id="permalink" rows="4"></textarea>
            </div>
        </div>
        <div class="tab-pane" id="tab-storage">
            <div class="form-group">
                <label>对象存储配置（options.storage_config：driver local/oss/cos/s3）</label>
                <textarea class="form-control" id="storage_config" rows="6"></textarea>
            </div>
        </div>
        <div class="tab-pane" id="tab-mail">
            <div class="form-group">
                <label>邮件配置（options.smtp_config）</label>
                <textarea class="form-control" id="smtp_config" rows="6"></textarea>
            </div>
        </div>
        <button class="btn bg-primary-500 text-white" id="save-options">保存设置</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tab 切换（zui.js 未内置 tabs 行为，仅有 nav-tabs 样式）
    document.querySelectorAll('#option-tabs a').forEach(a => a.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('#option-tabs li').forEach(li => li.classList.remove('active'));
        a.parentElement.classList.add('active');
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        const pane = document.querySelector(a.getAttribute('href'));
        if (pane) pane.classList.add('active');
    }));

    const keys = ['comment_config', 'permalink', 'storage_config', 'smtp_config'];
    const optionIdByKey = {}; // key → options.id，保存时据此走 update 而非 store（避开唯一索引冲突）
    let siteId = null;

    // 加载 options 配置组
    adminApi.post('/api/admin/option/search', {page: 1, pageSize: 100}).then(res => {
        adminApi.rows(res).forEach(o => {
            const key = o.optionKey ?? o.option_key;
            if (o.id) optionIdByKey[key] = o.id;
            if (keys.includes(key)) {
                const el = document.getElementById(key);
                if (el) {
                    const v = o.optionValue ?? o.option_value;
                    el.value = typeof v === 'string' ? v : JSON.stringify(v, null, 2);
                }
            }
            if (key === 'site_info') {
                try {
                    const info = JSON.parse(o.optionValue ?? o.option_value ?? '{}');
                    document.getElementById('site_info').value = [info.slogan, info.icp_number].filter(Boolean).join(' | ');
                } catch (e) {}
            }
        });
    });

    // 加载站点名称（sites 表，默认单站点第一条）
    adminApi.post('/api/admin/site/search', {page: 1, pageSize: 1}).then(res => {
        const site = adminApi.rows(res)[0];
        if (site) {
            siteId = site.id;
            document.getElementById('site_name').value = site.siteName ?? site.site_name ?? '';
        }
    });

    document.getElementById('save-options').addEventListener('click', () => {
        const tasks = [];
        if (siteId) {
            tasks.push(adminApi.put('/api/admin/site/update', {id: siteId, site_name: document.getElementById('site_name').value}));
        }
        keys.forEach(k => {
            const el = document.getElementById(k);
            if (el && el.value) {
                const oid = optionIdByKey[k];
                tasks.push(oid
                    ? adminApi.put('/api/admin/option/update', {id: oid, option_key: k, option_value: el.value})
                    : adminApi.post('/api/admin/option/store', {option_key: k, option_value: el.value}));
            }
        });
        Promise.all(tasks).then(() => alert('已保存'));
    });
</script>
@endpush
