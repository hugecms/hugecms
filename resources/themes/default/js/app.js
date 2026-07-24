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
                a.className = heading.tagName.toLowerCase() === 'h3' ? 'toc-link toc-link-sub' : 'toc-link';

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
                        link.classList.add('toc-active');
                    } else {
                        link.classList.remove('toc-active');
                    }
                });
            });
        } else {
            const emptyNotice = document.createElement('li');
            emptyNotice.className = 'toc-empty';
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
                searchModal.classList.add('is-open');
                const searchInput = document.getElementById('search-modal-input');
                if (searchInput) searchInput.focus();
            });
        });

        if (closeSearchBtn) {
            closeSearchBtn.addEventListener('click', () => {
                searchModal.classList.remove('is-open');
            });
        }

        // Keyboard Shortcut: Cmd/Ctrl + K
        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                searchModal.classList.toggle('is-open');
                const searchInput = document.getElementById('search-modal-input');
                if (searchInput && searchModal.classList.contains('is-open')) searchInput.focus();
            }
            if (e.key === 'Escape' && searchModal.classList.contains('is-open')) {
                searchModal.classList.remove('is-open');
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
                likeBtn.classList.add('is-liked');
                likeCountSpan.innerText = count;
            } else {
                count -= 1;
                liked = false;
                likeBtn.classList.remove('is-liked');
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
