/**
 * 前台主题浅色 / 深色模式切换
 *
 * - 读取 localStorage.theme 持久化偏好
 * - 未设置时跟随系统 prefers-color-scheme
 * - 通过给 <html> 添加/移除 .dark 类触发 Tailwind dark: 变体
 */
(function () {
    const STORAGE_KEY = 'theme';
    const CLASS_DARK = 'dark';

    function getSystemPrefersDark() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function isDarkMode() {
        return document.documentElement.classList.contains(CLASS_DARK);
    }

    function setDarkMode(dark) {
        if (dark) {
            document.documentElement.classList.add(CLASS_DARK);
        } else {
            document.documentElement.classList.remove(CLASS_DARK);
        }
    }

    function persistTheme(theme) {
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {
            // 隐私模式等可能导致 localStorage 不可用，静默降级
        }
    }

    function updateToggleButton(button) {
        const dark = isDarkMode();
        button.setAttribute('aria-pressed', String(dark));
        button.setAttribute(
            'aria-label',
            dark ? '切换到浅色模式' : '切换到深色模式'
        );
    }

    function initToggle() {
        const button = document.getElementById('theme-toggle');
        if (!button) {
            return;
        }

        updateToggleButton(button);

        button.addEventListener('click', function () {
            const nextDark = !isDarkMode();
            setDarkMode(nextDark);
            persistTheme(nextDark ? 'dark' : 'light');
            updateToggleButton(button);
        });
    }

    function initSystemPreferenceListener() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

        mediaQuery.addEventListener('change', function (event) {
            try {
                if (!localStorage.getItem(STORAGE_KEY)) {
                    setDarkMode(event.matches);
                    const button = document.getElementById('theme-toggle');
                    if (button) {
                        updateToggleButton(button);
                    }
                }
            } catch (e) {
                // 隐私模式等静默降级
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initToggle();
            initSystemPreferenceListener();
        });
    } else {
        initToggle();
        initSystemPreferenceListener();
    }
})();
