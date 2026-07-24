# 前台移除构建管线，主题资源原生直出

前台主题不再使用 Vite/Tailwind/npm 构建，主题的 `css/app.css` 与 `js/app.js` 为手写的原生文件，通过 `themes/{theme}/{path}` 路由（`ThemeAssetController`）在运行时直接伺服，Blade 布局用 `theme_asset()` 辅助函数引用；仓库根目录不再保留 `package.json`/`vite.config.js`。后台管理则迁移到独立的 Vue SPA（`packages/admin/`，自带 Vite 构建），由 Laravel 在同域 `/admin` 伺服其 `dist`。

这样前台主题保持零构建、即改即生效，主题包自包含可直接复制分发；代价是失去 Tailwind 工具类与自动前缀、压缩等构建期优化，分页等共享视图需使用语义化 class（见 `resources/views/vendor/pagination/default.blade.php`）由各主题自行实现样式。此决策取代 ADR 0003 中的 Vite 入口方案（主题目录布局与独立 css/js 入口的约定仍然保留）。
