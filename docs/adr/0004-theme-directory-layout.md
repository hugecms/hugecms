# 主题目录统一放在 resources/themes/{theme}

前台主题统一放在 `resources/themes/{theme}/` 目录下，其中 `views/` 存放 Blade 模板，`css/` 与 `js/` 分别存放样式和脚本原始文件。该结构把同一主题的视图与资源放在一起，便于主题作者打包、复制与版本控制；代价是需要自定义视图查找路径和 Vite 入口扫描逻辑。
