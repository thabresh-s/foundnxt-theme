/**
 * FoundNXT Theme — Main JS
 * Handles: dark mode, sticky header, mobile nav, search,
 * reading progress, TOC toggle, load more, copy link, share
 */
(function () {
  'use strict';

  /* ── Utilities ── */
  const $ = (s, ctx = document) => ctx.querySelector(s);
  const $$ = (s, ctx = document) => [...ctx.querySelectorAll(s)];
  const on = (el, ev, fn, opts) => el && el.addEventListener(ev, fn, opts);

  /* ══════════════════════════════════════
     DARK MODE
  ══════════════════════════════════════ */
  const DARK_KEY = 'fnx_theme';
  const body = document.body;
  const darkBtn = $('#dark-toggle');

  function applyTheme(theme) {
    // FIX: apply to BOTH html and body so CSS vars work before/after JS fires
    document.documentElement.setAttribute('data-theme', theme);
    document.documentElement.classList.toggle('dark-mode', theme === 'dark');
    document.documentElement.classList.toggle('light-mode', theme === 'light');
    body.setAttribute('data-theme', theme);
    body.classList.toggle('dark-mode', theme === 'dark');
    body.classList.toggle('light-mode', theme === 'light');
    localStorage.setItem(DARK_KEY, theme);
  }

  // Init: use saved preference, then OS preference
  (function initTheme() {
    const saved = localStorage.getItem(DARK_KEY);
    if (saved) { applyTheme(saved); return; }
    const osDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    applyTheme(osDark ? 'dark' : 'light');
  })();

  // Watch OS changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem(DARK_KEY)) applyTheme(e.matches ? 'dark' : 'light');
  });

  on(darkBtn, 'click', () => {
    const current = body.getAttribute('data-theme') || 'light';
    applyTheme(current === 'dark' ? 'light' : 'dark');
  });

  /* ══════════════════════════════════════
     STICKY HEADER
  ══════════════════════════════════════ */
  const header = $('#fnx-header');
  let lastY = 0;

  window.addEventListener('scroll', () => {
    const y = window.scrollY;
    if (header) {
      header.classList.toggle('scrolled', y > 10);
      header.classList.toggle('header-hidden', y > lastY && y > 200);
    }
    lastY = y;
  }, { passive: true });

  /* ══════════════════════════════════════
     TOP BAR CLOSE & COOKIE CONSENT
  ══════════════════════════════════════ */
  const topbar    = $('#fnx-topbar');
  const topClose  = $('#topbar-close') || $('.topbar-close');
  if (localStorage.getItem('fnx_topbar') === 'closed' && topbar) {
    topbar.style.display = 'none';
  }
  on(topClose, 'click', () => {
    if (topbar) {
      topbar.style.display = 'none';
      localStorage.setItem('fnx_topbar', 'closed');
    }
  });

  const cookieBanner = $('#fnx-cookie-banner');
  const cookieAccept = $('#fnx-accept-cookies');
  if (cookieBanner) {
    if (localStorage.getItem('fnx_cookie_consent') === 'true') {
      cookieBanner.style.display = 'none';
    } else {
      cookieBanner.style.display = 'block';
    }
    on(cookieAccept, 'click', () => {
      localStorage.setItem('fnx_cookie_consent', 'true');
      cookieBanner.style.display = 'none';
    });
  }

  /* ══════════════════════════════════════
     MOBILE NAV
  ══════════════════════════════════════ */
  const mobileToggle  = $('#mobile-toggle');
  const mobileNav     = $('#mobile-nav');
  const mobileClose   = $('#mobile-close');
  const overlay       = $('#mobile-overlay');

  function openMobileNav() {
    mobileNav && mobileNav.classList.add('open');
    overlay && overlay.classList.add('active');
    mobileToggle && mobileToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMobileNav() {
    mobileNav && mobileNav.classList.remove('open');
    overlay && overlay.classList.remove('active');
    mobileToggle && mobileToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }
  on(mobileToggle, 'click', openMobileNav);
  on(mobileClose,  'click', closeMobileNav);
  on(overlay,      'click', closeMobileNav);
  on(document, 'keydown', e => { if (e.key === 'Escape') closeMobileNav(); });

  /* ══════════════════════════════════════
     MOBILE SUB-MENU ACCORDION
     Uses CSS classes only — no inline styles
     that would conflict with stylesheet rules.
  ══════════════════════════════════════ */
  const mobileMenuParents = document.querySelectorAll('.mobile-menu .menu-item-has-children > a');
  mobileMenuParents.forEach(link => {
    // Inject a "Visit page" shortcut as the first sub-menu item so users
    // can navigate to the parent URL even though tapping the label toggles the accordion.
    const liParent = link.closest('li');
    const subParent = liParent ? liParent.querySelector(':scope > .sub-menu') : null;
    if (subParent && link.href && link.href !== '#' && !subParent.querySelector('.mobile-visit-parent')) {
      const labelText = link.querySelector('.nav-label') ? link.querySelector('.nav-label').textContent : link.textContent;
      const visitLi = document.createElement('li');
      visitLi.className = 'mobile-visit-parent';
      const visitA = document.createElement('a');
      visitA.href = link.href;
      visitA.innerHTML = 'View all <strong>' + labelText.trim() + '</strong> &rarr;';
      visitA.className = 'mobile-visit-link';
      visitLi.appendChild(visitA);
      subParent.insertBefore(visitLi, subParent.firstChild);
    }

    link.addEventListener('click', function(e) {
      const li  = this.closest('li');
      const sub = li.querySelector(':scope > .sub-menu');
      if (!sub) return;

      const isOpen = li.classList.contains('open');

      // If already open, let the browser navigate to the parent page normally
      if (isOpen) return;

      // First tap: prevent navigation and open the sub-menu
      e.preventDefault();

      // Close all siblings at same level
      const siblings = li.parentElement
        ? li.parentElement.querySelectorAll(':scope > .menu-item-has-children')
        : [];
      siblings.forEach(s => {
        if (s !== li) {
          s.classList.remove('open');
          const sm = s.querySelector(':scope > .sub-menu');
          if (sm) sm.classList.remove('open');
        }
      });

      // Open current
      li.classList.add('open');
      sub.classList.add('open');
    });
  });

  /* ══════════════════════════════════════
     DESKTOP NAV — KEYBOARD NAVIGATION
     (Arrow keys, Enter, Escape, Tab)
  ══════════════════════════════════════ */
  const primaryNav = $('#primary-nav');
  if (primaryNav) {
    const topItems = Array.from(primaryNav.querySelectorAll(':scope .nav-menu > li'));

    function getSubLinks(li) {
      const sub = li.querySelector(':scope > .sub-menu');
      return sub ? Array.from(sub.querySelectorAll(':scope > li > a')) : [];
    }
    function openDropdown(li) {
      const sub = li.querySelector(':scope > .sub-menu');
      if (!sub) return;
      li.classList.add('kb-open');
      sub.style.opacity = '1';
      sub.style.pointerEvents = 'all';
      sub.style.transform = 'translateX(-50%) translateY(0)';
      const parentLink = li.querySelector(':scope > a');
      if (parentLink) parentLink.setAttribute('aria-expanded', 'true');
      const first = sub.querySelector(':scope > li > a');
      if (first) { first.removeAttribute('tabindex'); first.focus(); }
      getSubLinks(li).forEach(a => a.removeAttribute('tabindex'));
    }
    function closeDropdown(li) {
      const sub = li.querySelector(':scope > .sub-menu');
      if (!sub) return;
      li.classList.remove('kb-open', 'js-open', 'click-open');
      sub.style.opacity = '';
      sub.style.pointerEvents = '';
      sub.style.transform = '';
      const parentLink = li.querySelector(':scope > a');
      if (parentLink) parentLink.setAttribute('aria-expanded', 'false');
      getSubLinks(li).forEach(a => a.setAttribute('tabindex', '-1'));
    }
    function closeAll() { topItems.forEach(closeDropdown); }

    topItems.forEach((li, idx) => {
      const parentLink = li.querySelector(':scope > a');
      const subs = getSubLinks(li);
      if (!parentLink) return;

      // Open on Enter if has children
      parentLink.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown' && subs.length) {
          e.preventDefault(); openDropdown(li);
        } else if (e.key === 'ArrowRight') {
          e.preventDefault();
          const next = topItems[idx + 1];
          if (next) next.querySelector(':scope > a')?.focus();
        } else if (e.key === 'ArrowLeft') {
          e.preventDefault();
          const prev = topItems[idx - 1];
          if (prev) prev.querySelector(':scope > a')?.focus();
        } else if (e.key === 'Escape') {
          closeAll();
        }
      });

      // Arrow nav within sub-menu
      subs.forEach((a, si) => {
        a.addEventListener('keydown', e => {
          if (e.key === 'ArrowDown') {
            e.preventDefault();
            subs[si + 1]?.focus();
          } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (si === 0) { closeDropdown(li); parentLink.focus(); }
            else subs[si - 1]?.focus();
          } else if (e.key === 'Escape' || e.key === 'Tab') {
            closeDropdown(li);
            if (e.key === 'Escape') { e.preventDefault(); parentLink.focus(); }
          }
        });
      });
    });

    // ── Click-only dropdown: NO hover opening on desktop ──
    // Clicking a parent with children toggles the dropdown.
    // Clicking the same parent again (when open) navigates to its URL.
    // Clicking outside closes all.
    topItems.forEach(li => {
      const parentLink = li.querySelector(':scope > a');
      const hasSub = !!li.querySelector(':scope > .sub-menu');
      if (!parentLink || !hasSub) return;

      parentLink.addEventListener('click', e => {
        if (e.detail === 0) return; // skip keyboard-synthetic clicks

        const isOpen = li.classList.contains('click-open');

        if (isOpen) {
          // Already open — second click navigates to the parent page
          li.classList.remove('click-open');
          closeDropdown(li);
          // allow default navigation
          return;
        }

        // First click: open dropdown, prevent navigation
        e.preventDefault();

        // Close any other open item first
        topItems.forEach(other => {
          if (other !== li) {
            other.classList.remove('click-open');
            closeDropdown(other);
          }
        });

        li.classList.add('click-open');
        // Show via inline style (CSS class .click-open also covers this)
        const sub = li.querySelector(':scope > .sub-menu');
        if (sub) {
          sub.style.opacity = '1';
          sub.style.pointerEvents = 'all';
          sub.style.transform = 'translateX(-50%) translateY(0)';
        }
        parentLink.setAttribute('aria-expanded', 'true');
      });
    });

    // Close all dropdowns when clicking anywhere outside the nav
    document.addEventListener('click', e => {
      if (!primaryNav.contains(e.target)) {
        topItems.forEach(li => {
          li.classList.remove('click-open');
        });
        closeAll();
      }
    });
  }

  /* ══════════════════════════════════════
     SEARCH
  ══════════════════════════════════════ */
  const searchToggle  = $('#search-toggle');
  const searchBar     = $('#header-search');
  const searchClose   = $('#search-close');
  const searchInput   = $('#search-input');

  function openSearch() {
    searchBar && searchBar.classList.add('active');
    searchBar && searchBar.setAttribute('aria-hidden', 'false');
    searchToggle && searchToggle.setAttribute('aria-expanded', 'true');
    setTimeout(() => searchInput && searchInput.focus(), 100);
  }
  function closeSearch() {
    searchBar && searchBar.classList.remove('active');
    searchBar && searchBar.setAttribute('aria-hidden', 'true');
    searchToggle && searchToggle.setAttribute('aria-expanded', 'false');
  }
  on(searchToggle, 'click', openSearch);
  on(searchClose,  'click', closeSearch);
  on(document, 'keydown', e => { if (e.key === 'Escape') closeSearch(); });

  /* ══════════════════════════════════════
     READING PROGRESS BAR
  ══════════════════════════════════════ */
  const progressFill = $('#progress-fill');
  const progressBar  = $('#reading-progress');

  if (progressFill && progressBar) {
    const updateProgress = () => {
      const content = $('#post-content') || document.body;
      const rect    = content.getBoundingClientRect();
      const total   = rect.height;
      const scrolled = Math.max(0, -rect.top);
      const pct     = Math.min(100, (scrolled / (total - window.innerHeight)) * 100);
      progressFill.style.width = pct + '%';
      progressBar.setAttribute('aria-valuenow', Math.round(pct));
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
  }

  /* ══════════════════════════════════════
     SIDEBAR TABLE OF CONTENTS — AUTO BUILD
  ══════════════════════════════════════ */
  (function buildSidebarTOC() {
    const tocList     = $('#toc-sidebar-list');
    const tocHeader   = $('#toc-sidebar-header');
    const tocToggle   = $('#toc-sidebar-toggle');
    const tocBody     = $('#toc-sidebar-body');
    const tocFill     = $('#toc-progress-fill');
    const postContent = $('#post-content');

    if (!tocList || !postContent) return;

    // Collect all H2 and H3 headings from post content
    const headings = $$('h2, h3', postContent);
    if (!headings.length) {
      tocList.innerHTML = '<li><span class="toc-empty-msg">No sections found.</span></li>';
      return;
    }

    // Build TOC items + add IDs to headings
    const items = headings.map((h, i) => {
      if (!h.id) {
        // generate slug from text
        const slug = 'toc-' + h.textContent.trim()
          .toLowerCase()
          .replace(/[^a-z0-9\s-]/g, '')
          .replace(/\s+/g, '-')
          .slice(0, 60);
        h.id = slug + (i > 0 ? '-' + i : '');
      }
      return { id: h.id, text: h.textContent.trim(), level: h.tagName.toLowerCase() };
    });

    // Render list
    tocList.innerHTML = items.map(item =>
      `<li class="toc-${item.level}">
        <a href="#${item.id}" data-toc-id="${item.id}">${escHtml(item.text)}</a>
       </li>`
    ).join('');

    // Smooth scroll on click
    $$('a[data-toc-id]', tocList).forEach(a => {
      on(a, 'click', e => {
        e.preventDefault();
        const target = document.getElementById(a.dataset.tocId);
        if (target) {
          const offset = 88; // sticky header height
          const top = target.getBoundingClientRect().top + window.scrollY - offset;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      });
    });

    // Active heading + reading progress on scroll
    const updateTOC = () => {
      const scrollY  = window.scrollY;
      const docH     = document.documentElement.scrollHeight - window.innerHeight;
      const pct      = docH > 0 ? Math.min(100, (scrollY / docH) * 100) : 0;
      if (tocFill) tocFill.style.width = pct + '%';

      let activeId = '';
      headings.forEach(h => {
        if (h.getBoundingClientRect().top < 120) activeId = h.id;
      });
      $$('a[data-toc-id]', tocList).forEach(a => {
        a.classList.toggle('toc-active', a.dataset.tocId === activeId);
      });

      // Auto-scroll active item into view within TOC
      const activeLink = tocList.querySelector('.toc-active');
      if (activeLink) {
        const parent = tocBody;
        if (parent) {
          const linkTop   = activeLink.offsetTop;
          const parentH   = parent.offsetHeight;
          const scrollTop = parent.scrollTop;
          if (linkTop < scrollTop || linkTop > scrollTop + parentH - 40) {
            parent.scrollTo({ top: linkTop - 60, behavior: 'smooth' });
          }
        }
      }
    };
    window.addEventListener('scroll', updateTOC, { passive: true });
    updateTOC();

    // Toggle collapse
    if (tocHeader && tocBody && tocToggle) {
      on(tocHeader, 'click', () => {
        const collapsed = tocBody.classList.toggle('hidden');
        tocToggle.classList.toggle('collapsed', collapsed);
        tocToggle.textContent = collapsed ? '›' : '⌄';
      });
    }
  })();

  /* ══════════════════════════════════════
     INLINE TOC TOGGLE (article body)
  ══════════════════════════════════════ */
  const inlineTocToggle = $('.toc-toggle');
  const inlineTocList   = $('.toc-list');
  on(inlineTocToggle, 'click', () => {
    if (!inlineTocList) return;
    const collapsed = inlineTocList.classList.toggle('collapsed');
    inlineTocToggle.textContent = collapsed ? '+' : '−';
  });

  /* ════════════════════════════════════
     PAGINATED POSTS (AJAX)
  ════════════════════════════════════ */
  const postsGrid      = $('#posts-grid');
  const paginationEl   = $('#hp-pagination');
  const paginationInfo = $('#hp-pagination-info');

  const CARD_ACCENTS = ['#4f46e5','#2563eb','#7c3aed','#d97706','#e11d48','#0891b2','#059669','#dc2626'];

  let paginState = {
    page:     1,
    maxPages: paginationEl ? parseInt(paginationEl.dataset.max || 1) : 1,
    cat:      paginationEl ? (paginationEl.dataset.cat || 0) : 0,
    total:    paginationEl ? parseInt(paginationEl.dataset.total || 0) : 0,
    perPage:  12,
    loading:  false,
  };

  function buildPostCard(post, idx) {
    const accentIdx = post.cat_id ? (parseInt(post.cat_id) % CARD_ACCENTS.length) : (idx % CARD_ACCENTS.length);
    const accent = CARD_ACCENTS[accentIdx];
    return `
    <article class="post-card" style="--card-accent:${accent}">
      <div class="post-card-accent-strip"></div>
      <div class="post-card-body">
        <div class="post-card-meta">
          <a href="${escHtml(post.cat_url)}" class="post-card-cat">${escHtml(post.category)}</a>
          <span class="post-card-date">${escHtml(post.date)}</span>
          <span class="post-card-read">${escHtml(post.read_time)}</span>
        </div>
        <a href="${escHtml(post.link)}" class="post-card-title">${escHtml(post.title)}</a>
        <p class="post-card-excerpt">${escHtml(post.excerpt)}</p>
        <div class="post-card-footer">
          <div class="post-card-author"><span>${escHtml(post.author)}</span></div>
          <a href="${escHtml(post.link)}" class="post-card-cta">Read →</a>
        </div>
      </div>
    </article>`;
  }

  function showSkeletons(count) {
    if (!postsGrid) return;
    postsGrid.innerHTML = Array(count).fill('<div class="post-card-skeleton"></div>').join('');
  }

  async function loadPage(page) {
    if (paginState.loading || !postsGrid || typeof fnxData === 'undefined') return;
    paginState.loading = true;
    showSkeletons(12);

    try {
      const form = new FormData();
      form.append('action',   'fnx_load_more');
      form.append('nonce',    fnxData.nonce);
      form.append('page',     page);
      form.append('category', paginState.cat);
      form.append('per_page', paginState.perPage);

      const res  = await fetch(fnxData.ajaxUrl, { method: 'POST', body: form });
      const json = await res.json();

      if (json.success && json.data.posts.length) {
        paginState.page     = page;
        paginState.maxPages = json.data.max_pages;
        paginState.total    = json.data.found;

        postsGrid.innerHTML = json.data.posts.map((p, i) => buildPostCard(p, i)).join('');

        // Staggered entrance animation
        postsGrid.querySelectorAll('.post-card').forEach((card, i) => {
          card.style.opacity = '0';
          card.style.transform = 'translateY(12px)';
          setTimeout(() => {
            card.style.transition = 'opacity .3s ease, transform .3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
          }, i * 35);
        });

        renderPagination();
        postsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
      } else {
        postsGrid.innerHTML = '<p style="color:var(--fnx-muted);grid-column:1/-1;text-align:center;padding:40px">No articles found.</p>';
        if (paginationEl) paginationEl.innerHTML = '';
      }
    } catch (e) {
      postsGrid.innerHTML = '<p style="color:var(--fnx-muted);grid-column:1/-1;text-align:center;padding:40px">Could not load articles.</p>';
    }
    paginState.loading = false;
  }

  function renderPagination() {
    if (!paginationEl) return;
    const { page, maxPages, total, perPage } = paginState;

    if (paginationInfo) {
      const from = (page - 1) * perPage + 1;
      const to   = Math.min(page * perPage, total);
      paginationInfo.textContent = total > 0 ? `Showing ${from}–${to} of ${total} articles` : '';
    }

    if (maxPages <= 1) { paginationEl.innerHTML = ''; return; }

    let html = '';
    html += `<button class="hp-page-btn hp-page-btn--nav" data-page="${page - 1}" ${page === 1 ? 'disabled' : ''} aria-label="Previous">‹</button>`;

    buildPageRange(page, maxPages).forEach(p => {
      if (p === '…') {
        html += `<span class="hp-page-ellipsis">…</span>`;
      } else {
        html += `<button class="hp-page-btn${p === page ? ' hp-page-btn--active' : ''}" data-page="${p}" ${p === page ? 'aria-current="page"' : ''}>${p}</button>`;
      }
    });

    html += `<button class="hp-page-btn hp-page-btn--nav" data-page="${page + 1}" ${page === maxPages ? 'disabled' : ''} aria-label="Next">›</button>`;
    paginationEl.innerHTML = html;

    paginationEl.querySelectorAll('.hp-page-btn:not([disabled]):not(.hp-page-btn--active)').forEach(btn => {
      btn.addEventListener('click', () => loadPage(parseInt(btn.dataset.page)));
    });
  }

  function buildPageRange(current, total) {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
    const pages = [];
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) pages.push(i);
      pages.push('…'); pages.push(total);
    } else if (current >= total - 3) {
      pages.push(1); pages.push('…');
      for (let i = total - 4; i <= total; i++) pages.push(i);
    } else {
      pages.push(1); pages.push('…');
      for (let i = current - 1; i <= current + 1; i++) pages.push(i);
      pages.push('…'); pages.push(total);
    }
    return pages;
  }

  // Initial pagination render (page 1 already server-rendered)
  if (paginationEl && postsGrid) renderPagination()

  /* ══════════════════════════════════════
     CATEGORY FILTER
  ══════════════════════════════════════ */
  $$('.cat-filter-chip').forEach(chip => {
    on(chip, 'click', function () {
      $$('.cat-filter-chip').forEach(c => c.classList.remove('active'));
      this.classList.add('active');
      const cat = parseInt(this.dataset.cat || 0);
      // Reset pagination state and reload page 1 for this category
      paginState.cat      = cat;
      paginState.page     = 1;
      paginState.maxPages = 1;
      paginState.total    = 0;
      if (paginationEl) paginationEl.dataset.cat = cat;
      loadPage(1);
    });
  });

  /* ══════════════════════════════════════
     COPY LINK
  ══════════════════════════════════════ */
  $$('.share-copy').forEach(btn => {
    on(btn, 'click', async () => {
      const url = btn.dataset.url || location.href;
      try {
        await navigator.clipboard.writeText(url);
        const orig = btn.textContent;
        btn.textContent = '✅';
        setTimeout(() => btn.textContent = orig, 2000);
      } catch (e) {}
    });
  });

  /* ══════════════════════════════════════
     SMOOTH SCROLL FOR ANCHOR LINKS
  ══════════════════════════════════════ */
  $$('a[href^="#"]').forEach(a => {
    on(a, 'click', e => {
      const target = document.getElementById(a.getAttribute('href').slice(1));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ══════════════════════════════════════
     LAZY IMAGES — IntersectionObserver
  ══════════════════════════════════════ */
  if ('IntersectionObserver' in window) {
    const imgObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          const img = e.target;
          if (img.dataset.src) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
          imgObs.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });
    $$('img[data-src]').forEach(img => imgObs.observe(img));
  }

  /* ══════════════════════════════════════
     ROADMAP REVEAL — "What You'll Find Here"
  ══════════════════════════════════════ */
  (function initRoadmapReveal() {
    const items = $$('.hp-roadmap-item');
    if (!items.length) return;
    if (!('IntersectionObserver' in window)) {
      items.forEach(el => el.classList.add('is-visible'));
      return;
    }
    const roadmapObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          roadmapObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2, rootMargin: '0px 0px -60px 0px' });
    items.forEach(el => roadmapObs.observe(el));
  })();

  /* ══════════════════════════════════════
     GENERIC SCROLL REVEAL — .fnx-reveal
     Section headers, bento cards, category chips,
     tools teaser card, etc.
  ══════════════════════════════════════ */
  (function initFnxReveal() {
    const items = $$('.fnx-reveal');
    if (!items.length) return;
    if (!('IntersectionObserver' in window)) {
      items.forEach(el => el.classList.add('is-visible'));
      return;
    }
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    items.forEach(el => revealObs.observe(el));
  })();

  /* ── Helpers ── */
  function escHtml(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }
  function stripTags(s) {
    const d = document.createElement('div');
    d.innerHTML = s;
    return d.textContent || '';
  }

  /* ══════════════════════════════════════
     HOMEPAGE CATEGORY TABS (index.php)
  ══════════════════════════════════════ */
  (function initHPCats() {
    const chips  = $$('.hp-cov-chip');
    const tabs   = $$('.hp-cat-tab');
    const panels = $$('.hp-cat-panel');
    if (!chips.length && !tabs.length) return;

    function switchCat(cid) {
      chips.forEach(c  => c.classList.toggle('active', c.dataset.cid === cid));
      tabs.forEach(t   => t.classList.toggle('active', t.dataset.cid === cid));
      panels.forEach(p => p.classList.toggle('active', p.id === 'hp-cp-' + cid));
    }
    chips.forEach(c => on(c, 'click', () => switchCat(c.dataset.cid)));
    tabs.forEach(t  => on(t, 'click', () => switchCat(t.dataset.cid)));
  })();


})();