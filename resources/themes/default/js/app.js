document.addEventListener('DOMContentLoaded', () => {
    // 1. Reading progress bar
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

    // 2. Generate Table of Contents (TOC) dynamically if TOC container exists
    const tocList = document.getElementById('article-toc-list');
    const articleBody = document.querySelector('article.prose');

    if (tocList && articleBody) {
        const headings = articleBody.querySelectorAll('h2, h3');
        if (headings.length > 0) {
            headings.forEach((heading, index) => {
                if (!heading.id) {
                    heading.id = `heading-${index}`;
                }

                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = `#${heading.id}`;
                a.textContent = heading.textContent;
                a.className = `block text-xs transition-colors duration-150 py-1 ${
                    heading.tagName.toLowerCase() === 'h3' ? 'pl-3 text-slate-500 dark:text-slate-400' : 'font-medium text-slate-700 dark:text-slate-300'
                } hover:text-amber-600 dark:hover:text-amber-400`;

                li.appendChild(a);
                tocList.appendChild(li);
            });

            // Highlight Active TOC link on scroll
            window.addEventListener('scroll', () => {
                let currentId = '';
                headings.forEach(heading => {
                    const top = heading.getBoundingClientRect().top;
                    if (top <= 120) {
                        currentId = heading.id;
                    }
                });

                const links = tocList.querySelectorAll('a');
                links.forEach(link => {
                    if (link.getAttribute('href') === `#${currentId}`) {
                        link.classList.add('text-amber-600', 'dark:text-amber-400', 'font-bold');
                    } else {
                        link.classList.remove('text-amber-600', 'dark:text-amber-400', 'font-bold');
                    }
                });
            });
        } else {
            const emptyNotice = document.createElement('li');
            emptyNotice.className = 'text-xs text-slate-400 dark:text-slate-500 italic';
            emptyNotice.textContent = '本文暂无章节标题';
            tocList.appendChild(emptyNotice);
        }
    }

    // 3. Search Modal Trigger logic
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

        // Keyboard Shortcut: Cmd/Ctrl + K
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

    // 4. Interactive Like / Applaud Button Placeholder
    const likeBtn = document.getElementById('like-article-btn');
    const likeCountSpan = document.getElementById('like-count');
    if (likeBtn && likeCountSpan) {
        let count = parseInt(likeCountSpan.innerText || '0', 10);
        let liked = false;

        likeBtn.addEventListener('click', () => {
            if (!liked) {
                count += 1;
                liked = true;
                likeBtn.classList.add('bg-amber-100', 'text-amber-700', 'dark:bg-amber-950/60', 'dark:text-amber-400');
                likeCountSpan.innerText = count;
            } else {
                count -= 1;
                liked = false;
                likeBtn.classList.remove('bg-amber-100', 'text-amber-700', 'dark:bg-amber-950/60', 'dark:text-amber-400');
                likeCountSpan.innerText = count;
            }
        });
    }

    // 5. Copy Article Link Feature
    const copyLinkBtn = document.getElementById('copy-article-link');
    if (copyLinkBtn) {
        copyLinkBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const originalText = copyLinkBtn.innerText;
                copyLinkBtn.innerText = '已复制链接 ✓';
                setTimeout(() => {
                    copyLinkBtn.innerText = originalText;
                }, 2000);
            });
        });
    }
});
