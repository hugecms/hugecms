# 每个主题使用独立的 Vite 入口

每个主题拥有独立的 CSS/JS 入口（如 `resources/themes/{theme}/css/app.css` 和 `resources/themes/{theme}/js/app.js`），由 Vite 分别打包。渲染时根据当前主题加载对应资源。这样主题可以完全控制样式与交互，但会增加构建入口数量；对于仅改颜色的轻量主题会造成一定冗余。
