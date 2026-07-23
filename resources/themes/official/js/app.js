document.addEventListener('DOMContentLoaded', () => {
    // 1. Reading progress
    const progressBar = document.getElementById('reading-progress');
    const articleContainer = document.getElementById('article-content');

    if (progressBar && articleContainer) {
        window.addEventListener('scroll', () => {
            const totalHeight = articleContainer.clientHeight;
            const articleTop = articleContainer.offsetTop;
            const scrollPosition = window.scrollY - articleTop;
            const windowHeight = window.innerHeight;

            if (scrollPosition < 0) {
                progressBar.style.width = '0%';
                return;
            }

            const percentage = Math.min(100, Math.max(0, (scrollPosition / (totalHeight - windowHeight + 150)) * 100));
            progressBar.style.width = `${percentage}%`;
        });
    }

    // 2. AntD Collapse Style FAQ Toggle
    const faqButtons = document.querySelectorAll('.faq-button');
    faqButtons.forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('.faq-icon');
            
            content.classList.toggle('hidden');
            if (icon) {
                icon.classList.toggle('rotate-90');
            }
        });
    });

    // 3. Copy Quick Start Command
    const copyCmdBtn = document.getElementById('copy-install-cmd');
    if (copyCmdBtn) {
        copyCmdBtn.addEventListener('click', () => {
            const cmdText = document.getElementById('install-cmd-text')?.innerText || 'composer create-project hugecms/hugecms';
            navigator.clipboard.writeText(cmdText).then(() => {
                const originalText = copyCmdBtn.innerText;
                copyCmdBtn.innerText = '已复制 ✓';
                setTimeout(() => {
                    copyCmdBtn.innerText = originalText;
                }, 2000);
            });
        });
    }

    // 4. Search Modal Toggle
    const searchModal = document.getElementById('search-modal');
    const searchTriggers = document.querySelectorAll('.search-trigger');
    const closeSearchBtn = document.getElementById('close-search-modal');

    if (searchModal) {
        searchTriggers.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                searchModal.classList.remove('hidden');
                searchModal.classList.add('flex');
                const searchInput = document.getElementById('search-modal-input');
                if (searchInput) searchInput.focus();
            });
        });

        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', () => {
                searchModal.classList.add('hidden');
                searchModal.classList.remove('flex');
            });
        }

        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                searchModal.classList.toggle('hidden');
                searchModal.classList.toggle('flex');
                const searchInput = document.getElementById('search-modal-input');
                if (searchInput && !searchModal.classList.contains('hidden')) searchInput.focus();
            }
            if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
                searchModal.classList.add('hidden');
                searchModal.classList.remove('flex');
            }
        });
    }
});
