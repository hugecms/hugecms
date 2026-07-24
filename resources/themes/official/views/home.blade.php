@extends('layouts.app')

@section('title', \App\Support\SiteSetting::get('site_title', config('app.name')) . ' — 新一代企业级内容管理系统')
@section('description', \App\Support\SiteSetting::get('site_description', '基于 Laravel 13 与 Filament 5 打造的新一代企业级通用内容管理系统。低门槛交付、私有化部署与极致扩展性。'))
@section('keywords', \App\Support\SiteSetting::get('site_keywords', 'HugeCMS,Laravel CMS,Filament,企业级CMS,内容管理系统'))

@section('content')
<div class="home-content">

    {{-- Hero Landing Section --}}
    <section class="hero-section">
        <div class="container-wide hero-inner">

            {{-- AntD Tag Badge --}}
            <div>
                <span class="tag">
                    <span class="tag-dot"></span>
                    HugeCMS 1.0 正式发布：基于 Laravel 13 与 Filament 5
                </span>
            </div>

            {{-- Main Headline --}}
            <h1 class="hero-title">
                重新定义企业级内容管理 <br class="visible-sm">
                <span class="hero-title-accent">更快交付 · 私有部署 · 极简维护</span>
            </h1>

            {{-- Subtitle --}}
            <p class="hero-subtitle">
                HugeCMS 为企业客户、开发团队与运营人员提供低门槛、高绩效的单体内容管理体系。内置 RBAC 权限、可视化 Page Builder 与媒体托管，极速响应客户定制。
            </p>

            {{-- Action Buttons & AntD Command Input Group --}}
            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary btn-lg">
                    免费体验控制台 →
                </a>

                {{-- Command Box --}}
                <div class="command-box">
                    <span class="command-prompt">$</span>
                    <span id="install-cmd-text" class="command-text">composer create-project hugecms/hugecms</span>
                    <button id="copy-install-cmd" type="button" class="command-copy">
                        复制
                    </button>
                </div>
            </div>

            {{-- Metric Cards Strip --}}
            <div class="metrics-grid">
                <div class="metric">
                    <div class="metric-value">100%</div>
                    <div class="metric-label">私有化独立部署</div>
                </div>
                <div class="metric">
                    <div class="metric-value metric-value-accent">80%+</div>
                    <div class="metric-label">开箱即用需求覆盖</div>
                </div>
                <div class="metric">
                    <div class="metric-value">Laravel 13</div>
                    <div class="metric-label">原生现代架构底座</div>
                </div>
                <div class="metric">
                    <div class="metric-value metric-value-accent">&lt; 2 小时</div>
                    <div class="metric-label">极速新客户交付</div>
                </div>
            </div>
        </div>
    </section>

    {{-- AntD Bento Grid Features --}}
    <section id="features" class="container-wide stack-8">
        <div class="section-head">
            <span class="tag">企业级核心特性</span>
            <h2 class="section-title">全场景高效内容引擎</h2>
        </div>

        <div class="features-grid">

            {{-- Feature 1 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    📝
                </div>
                <h3 class="feature-title">全功能内容编排引擎</h3>
                <p class="feature-desc">
                    提供完整的文章草稿、待审核、已发布与软删除工作流，支持多级树形分类、关联标签、置顶/推荐以及独立 SEO TDK 设置。
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    🧱
                </div>
                <h3 class="feature-title">可视化 Page Builder</h3>
                <p class="feature-desc">
                    自由组合 Hero 首屏、双列图文、Call-to-Action 行动号召、富文本与动态集锦区块，无需代码即可拼装高转化率商业落地页。
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    🔒
                </div>
                <h3 class="feature-title">RBAC 细粒度权限与审计</h3>
                <p class="feature-desc">
                    预置超级管理员、编辑、运营、客服与审计角色，基于 Policy 的菜单与数据操作粒度控制，全量记录用户行为审计日志。
                </p>
            </div>

            {{-- Feature 4 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    🖼️
                </div>
                <h3 class="feature-title">Spatie 媒体资产全托管</h3>
                <p class="feature-desc">
                    集成 Spatie Media Library 引擎，支持图片与文档拖拽上传、资源分类，以及自动多尺寸切图与 WebP 高压缩比转化。
                </p>
            </div>

            {{-- Feature 5 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    🧰
                </div>
                <h3 class="feature-title">运营与 SEO 自动增强</h3>
                <p class="feature-desc">
                    内置轮播 Banner、弹窗公告、友情链接、全站 Sitemap.xml 自动生成与独立访问 PV/UV 数据分析组件。
                </p>
            </div>

            {{-- Feature 6 --}}
            <div class="card feature-card">
                <div class="feature-icon">
                    ⚡
                </div>
                <h3 class="feature-title">Vite 3 + Tailwind 4 极速构建</h3>
                <p class="feature-desc">
                    基于 Vite 3 原生 CSS 插件编译，主题包支持视图与资产自动降级回退机制，页面毫秒级响应。
                </p>
            </div>

        </div>
    </section>

    {{-- AntD Tech Specifications Table --}}
    <section id="tech-specs" class="container-wide stack-6">
        <div class="card tech-card">
            <div class="tech-card-head">
                <h2 class="tech-card-title">技术规范与方案对比</h2>
                <p class="section-subtitle">对比 HugeCMS 与其他常规方案的技术指标与维护成本。</p>
            </div>

            <div class="tech-table-wrap">
                <table class="tech-table">
                    <thead>
                        <tr>
                            <th>对比维度</th>
                            <th class="col-highlight">HugeCMS (Laravel 13 + Filament)</th>
                            <th class="col-dim">传统 Headless 方案</th>
                            <th class="col-dim">传统老旧 CMS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="cell-head">交付周期</td>
                            <td class="col-highlight">最快 2 小时完成定制部署</td>
                            <td>需要前后端分离联调 (1-2周)</td>
                            <td>插件冲突复杂，代码陈旧</td>
                        </tr>
                        <tr>
                            <td class="cell-head">部署运维</td>
                            <td class="col-highlight">标准单体架构，一键私有化部署</td>
                            <td>多服务部署，维护成本高</td>
                            <td>安全漏洞多，更新易崩溃</td>
                        </tr>
                        <tr>
                            <td class="cell-head">后台 UI 体验</td>
                            <td class="col-highlight">Filament 5 原生响应式与暗黑模式</td>
                            <td>需要单独开发前端后台</td>
                            <td>界面老旧，缺乏现代审美</td>
                        </tr>
                        <tr>
                            <td class="cell-head">二次开发门槛</td>
                            <td class="col-highlight">Laravel 生态标准规范，上手极快</td>
                            <td>技术栈割裂，维护人手翻倍</td>
                            <td>冗余 hook 多，缺乏现代化标准</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Articles Grid --}}
    @if ($articles->count() > 0)
        <section class="container-wide stack-6">
            <div class="stack-1">
                <span class="tag">官方动态</span>
                <h2 class="section-title-sm">最新发布与专栏</h2>
            </div>

            <div class="articles-grid">
                @foreach ($articles->take(3) as $article)
                    @php
                        $coverUrl = $article->getFirstMediaUrl('cover', 'medium') ?: ($article->cover_image ? asset('storage/'.$article->cover_image) : null);
                    @endphp
                    <article class="card article-card">
                        <div class="article-card-body">
                            @if ($coverUrl)
                                <div class="article-card-cover">
                                    <img src="{{ $coverUrl }}" alt="{{ $article->title }}">
                                </div>
                            @endif

                            <div class="article-card-date">
                                <time>{{ $article->published_at ? $article->published_at->format('Y-m-d') : $article->created_at->format('Y-m-d') }}</time>
                            </div>

                            <h3 class="article-card-title">
                                <a href="{{ route('article.show', $article->slug) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        </div>

                        <div class="article-card-footer">
                            <a href="{{ route('article.show', $article->slug) }}">阅读全文 →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- AntD Collapse FAQ Section --}}
    <section id="faq" class="container-narrow stack-6">
        <div class="section-head">
            <span class="tag">常见问题</span>
            <h2 class="section-title">答疑与解惑</h2>
        </div>

        <div class="faq-list">
            <div class="card faq-item">
                <button type="button" class="faq-button">
                    <span>HugeCMS 是否适合私有化部署到客户服务器？</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="faq-content hidden">
                    完全适合。HugeCMS 采用纯正的开源 Laravel 13 单体架构，没有任何云依赖或黑盒组件，支持标准 MySQL/PostgreSQL 及常规 Nginx/Apache 环境部署。
                </div>
            </div>

            <div class="card faq-item">
                <button type="button" class="faq-button">
                    <span>是否支持多主题自由切换与多级回退？</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="faq-content hidden">
                    支持。系统的 ThemeRegistry 支持从后台一键切换活动主题；当新主题缺少某视图文件时，系统会自动优雅回退至默认主题进行渲染。
                </div>
            </div>

            <div class="card faq-item">
                <button type="button" class="faq-button">
                    <span>可视化 Page Builder 区块如何扩展？</span>
                    <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div class="faq-content hidden">
                    在后台 Page Builder 中配置 Block 组件，并在主题目录下的 `views/blocks/` 中放置相对应的 Blade 模板即可完成新区块扩展。
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="container-wide">
        <div class="card cta-panel">
            <h2 class="cta-title">准备好开启高效交付体验了吗？</h2>
            <p class="cta-desc">
                立即使用 Composer 一键初始化您的 HugeCMS 项目，享受极速构建与私有化部署带来的快乐。
            </p>
            <div class="cta-action">
                <a href="{{ route('register') }}" class="btn-primary btn-cta-lg">
                    立即开启控制台试用
                </a>
            </div>
        </div>
    </section>

</div>
@endsection
