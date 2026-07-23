@extends('layouts.app')

@section('title', \App\Support\SiteSetting::get('site_title', config('app.name')) . ' — 新一代企业级内容管理系统')
@section('description', \App\Support\SiteSetting::get('site_description', '基于 Laravel 13 与 Filament 5 打造的新一代企业级通用内容管理系统。低门槛交付、私有化部署与极致扩展性。'))
@section('keywords', \App\Support\SiteSetting::get('site_keywords', 'HugeCMS,Laravel CMS,Filament,企业级CMS,内容管理系统'))

@section('content')
<div class="space-y-16 py-8">
    
    {{-- Hero Landing Section --}}
    <section class="relative overflow-hidden pt-4 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            
            {{-- AntD Tag Badge --}}
            <div class="inline-flex items-center gap-2">
                <span class="antd-tag">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1677FF] inline-block mr-1"></span>
                    HugeCMS 1.0 正式发布：基于 Laravel 13 与 Filament 5
                </span>
            </div>

            {{-- Main Headline --}}
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-slate-900 dark:text-white leading-tight max-w-4xl mx-auto">
                重新定义企业级内容管理 <br class="hidden sm:inline">
                <span class="text-[#1677FF]">更快交付 · 私有部署 · 极简维护</span>
            </h1>

            {{-- Subtitle --}}
            <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                HugeCMS 为企业客户、开发团队与运营人员提供低门槛、高绩效的单体内容管理体系。内置 RBAC 权限、可视化 Page Builder 与媒体托管，极速响应客户定制。
            </p>

            {{-- Action Buttons & AntD Command Input Group --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="{{ route('register') }}" class="antd-btn-primary px-6 py-2.5 text-sm shadow-md">
                    免费体验控制台 →
                </a>

                {{-- Command Box --}}
                <div class="flex items-center justify-between gap-2 px-3 py-1.5 rounded border border-[#d9d9d9] dark:border-[#424242] bg-white dark:bg-[#1f1f1f] text-xs font-mono">
                    <span class="text-[#1677FF] font-bold">$</span>
                    <span id="install-cmd-text" class="text-slate-700 dark:text-slate-300">composer create-project hugecms/hugecms</span>
                    <button id="copy-install-cmd" type="button" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-[#1677FF] text-[11px]">
                        复制
                    </button>
                </div>
            </div>

            {{-- Metric Cards Strip --}}
            <div class="pt-8 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto border-t border-[#f0f0f0] dark:border-[#303030]">
                <div class="space-y-0.5">
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">100%</div>
                    <div class="text-xs text-slate-500 font-medium">私有化独立部署</div>
                </div>
                <div class="space-y-0.5">
                    <div class="text-2xl font-bold text-[#1677FF]">80%+</div>
                    <div class="text-xs text-slate-500 font-medium">开箱即用需求覆盖</div>
                </div>
                <div class="space-y-0.5">
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">Laravel 13</div>
                    <div class="text-xs text-slate-500 font-medium">原生现代架构底座</div>
                </div>
                <div class="space-y-0.5">
                    <div class="text-2xl font-bold text-[#1677FF]">&lt; 2 小时</div>
                    <div class="text-xs text-slate-500 font-medium">极速新客户交付</div>
                </div>
            </div>
        </div>
    </section>

    {{-- AntD Bento Grid Features --}}
    <section id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="text-center space-y-2 max-w-2xl mx-auto">
            <span class="antd-tag">企业级核心特性</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">全场景高效内容引擎</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Feature 1 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    📝
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">全功能内容编排引擎</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    提供完整的文章草稿、待审核、已发布与软删除工作流，支持多级树形分类、关联标签、置顶/推荐以及独立 SEO TDK 设置。
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    🧱
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">可视化 Page Builder</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    自由组合 Hero 首屏、双列图文、Call-to-Action 行动号召、富文本与动态集锦区块，无需代码即可拼装高转化率商业落地页。
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    🔒
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">RBAC 细粒度权限与审计</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    预置超级管理员、编辑、运营、客服与审计角色，基于 Policy 的菜单与数据操作粒度控制，全量记录用户行为审计日志。
                </p>
            </div>

            {{-- Feature 4 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    🖼️
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Spatie 媒体资产全托管</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    集成 Spatie Media Library 引擎，支持图片与文档拖拽上传、资源分类，以及自动多尺寸切图与 WebP 高压缩比转化。
                </p>
            </div>

            {{-- Feature 5 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    🧰
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">运营与 SEO 自动增强</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    内置轮播 Banner、弹窗公告、友情链接、全站 Sitemap.xml 自动生成与独立访问 PV/UV 数据分析组件。
                </p>
            </div>

            {{-- Feature 6 --}}
            <div class="antd-card p-6 space-y-3">
                <div class="w-10 h-10 rounded bg-[#E6F4FF] dark:bg-blue-950 text-[#1677FF] flex items-center justify-center font-bold text-base">
                    ⚡
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Vite 3 + Tailwind 4 极速构建</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    基于 Vite 3 原生 CSS 插件编译，主题包支持视图与资产自动降级回退机制，页面毫秒级响应。
                </p>
            </div>

        </div>
    </section>

    {{-- AntD Tech Specifications Table --}}
    <section id="tech-specs" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="antd-card p-6 sm:p-8 space-y-6">
            <div class="space-y-1">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">技术规范与方案对比</h2>
                <p class="text-xs text-slate-500">对比 HugeCMS 与其他常规方案的技术指标与维护成本。</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-[#fafafa] dark:bg-[#1f1f1f] text-slate-900 dark:text-white font-bold border-b border-[#f0f0f0] dark:border-[#303030]">
                        <tr>
                            <th class="p-3">对比维度</th>
                            <th class="p-3 text-[#1677FF] font-bold">HugeCMS (Laravel 13 + Filament)</th>
                            <th class="p-3 text-slate-400">传统 Headless 方案</th>
                            <th class="p-3 text-slate-400">传统老旧 CMS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f0f0f0] dark:divide-[#303030]">
                        <tr>
                            <td class="p-3 font-medium text-slate-900 dark:text-white">交付周期</td>
                            <td class="p-3 font-bold text-[#1677FF]">最快 2 小时完成定制部署</td>
                            <td class="p-3">需要前后端分离联调 (1-2周)</td>
                            <td class="p-3">插件冲突复杂，代码陈旧</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium text-slate-900 dark:text-white">部署运维</td>
                            <td class="p-3 font-bold text-[#1677FF]">标准单体架构，一键私有化部署</td>
                            <td class="p-3">多服务部署，维护成本高</td>
                            <td class="p-3">安全漏洞多，更新易崩溃</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium text-slate-900 dark:text-white">后台 UI 体验</td>
                            <td class="p-3 font-bold text-[#1677FF]">Filament 5 原生响应式与暗黑模式</td>
                            <td class="p-3">需要单独开发前端后台</td>
                            <td class="p-3">界面老旧，缺乏现代审美</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium text-slate-900 dark:text-white">二次开发门槛</td>
                            <td class="p-3 font-bold text-[#1677FF]">Laravel 生态标准规范，上手极快</td>
                            <td class="p-3">技术栈割裂，维护人手翻倍</td>
                            <td class="p-3">冗余 hook 多，缺乏现代化标准</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Articles Grid --}}
    @if ($articles->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="space-y-1">
                <span class="antd-tag">官方动态</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">最新发布与专栏</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($articles->take(3) as $article)
                    @php
                        $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                    @endphp
                    <article class="antd-card p-5 flex flex-col justify-between space-y-3">
                        <div class="space-y-2">
                            @if ($coverUrl)
                                <div class="overflow-hidden rounded aspect-[16/10] bg-slate-100 dark:bg-slate-900">
                                    <img src="{{ $coverUrl }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            <div class="text-[11px] text-slate-400 font-mono">
                                <time>{{ $article->published_at ? $article->published_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</time>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 dark:text-white hover:text-[#1677FF] transition-colors line-clamp-2">
                                <a href="{{ route('article.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        </div>

                        <div class="pt-2 border-t border-[#f0f0f0] dark:border-[#303030] text-xs font-semibold text-[#1677FF]">
                            <a href="{{ route('article.show', $article->slug) }}">阅读全文 →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- AntD Collapse FAQ Section --}}
    <section id="faq" class="max-w-4xl mx-auto px-4 sm:px-6 space-y-6">
        <div class="text-center space-y-1">
            <span class="antd-tag">常见问题</span>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">答疑与解惑</h2>
        </div>

        <div class="space-y-3">
            <div class="antd-card p-4 space-y-2">
                <button type="button" class="faq-button w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-xs">
                    <span>HugeCMS 是否适合私有化部署到客户服务器？</span>
                    <svg class="faq-icon w-4 h-4 transition-transform duration-200 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="hidden text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-2 border-t border-[#f0f0f0] dark:border-[#303030]">
                    完全适合。HugeCMS 采用纯正的开源 Laravel 13 单体架构，没有任何云依赖或黑盒组件，支持标准 MySQL/PostgreSQL 及常规 Nginx/Apache 环境部署。
                </div>
            </div>

            <div class="antd-card p-4 space-y-2">
                <button type="button" class="faq-button w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-xs">
                    <span>是否支持多主题自由切换与多级回退？</span>
                    <svg class="faq-icon w-4 h-4 transition-transform duration-200 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="hidden text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-2 border-t border-[#f0f0f0] dark:border-[#303030]">
                    支持。系统的 ThemeRegistry 支持从后台一键切换活动主题；当新主题缺少某视图文件时，系统会自动优雅回退至默认主题进行渲染。
                </div>
            </div>

            <div class="antd-card p-4 space-y-2">
                <button type="button" class="faq-button w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-xs">
                    <span>可视化 Page Builder 区块如何扩展？</span>
                    <svg class="faq-icon w-4 h-4 transition-transform duration-200 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="hidden text-xs text-slate-500 dark:text-slate-400 leading-relaxed pt-2 border-t border-[#f0f0f0] dark:border-[#303030]">
                    在后台 Page Builder 中配置 Block 组件，并在主题目录下的 `views/blocks/` 中放置相对应的 Blade 模板即可完成新区块扩展。
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="antd-card bg-gradient-to-r from-slate-900 to-[#141414] text-white p-8 sm:p-12 text-center space-y-5 shadow-antd-s2">
            <h2 class="text-2xl sm:text-4xl font-bold text-white tracking-tight">准备好开启高效交付体验了吗？</h2>
            <p class="text-slate-400 text-xs sm:text-sm max-w-lg mx-auto leading-relaxed">
                立即使用 Composer 一键初始化您的 HugeCMS 项目，享受极速构建与私有化部署带来的快乐。
            </p>
            <div class="pt-2">
                <a href="{{ route('register') }}" class="antd-btn-primary px-6 py-2.5 text-xs shadow-md">
                    立即开启控制台试用
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
