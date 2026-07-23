{{--
    阻塞式主题初始化脚本，必须在 <head> 中、Vite CSS 之前引入。
    读取 localStorage.theme，无存储时跟随系统 prefers-color-scheme。
--}}
<script>
    (function () {
        try {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = stored === 'dark' || (!stored && prefersDark);

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            // 隐私模式等可能导致 localStorage 不可用，静默降级
        }
    })();
</script>
