/**
 * FoundNXT Modern Design System JavaScript
 * Version: 2.0.0
 * 
 * Handles:
 * - "What's New?" banner dismissal persistence
 * - Accessible single search overlay with focus trap and Esc close
 * - Cookie consent (Accept, Decline, Preferences) state in localStorage
 * - Tools "Coming Soon" waitlist submission state
 * - Contact form inline validation & submission states
 * - Reading progress bar fallback
 * - Light/Dark mode sync with prefers-color-scheme
 */

(function () {
  'use strict';

  // ── 1. WHAT'S NEW TOP BANNER DISMISSAL (SAVED IN COOKIE) ──
  function initTopBanner() {
    const banner = document.getElementById('fnx-top-banner');
    const closeBtn = document.getElementById('top-banner-close');
    if (!banner || !closeBtn) return;

    if (document.cookie.indexOf('fnx_announcement_dismissed=1') !== -1 || localStorage.getItem('fnx_banner_dismissed_v2') === 'true') {
      banner.style.display = 'none';
      banner.classList.add('is-dismissed');
      return;
    }

    closeBtn.addEventListener('click', function () {
      banner.style.display = 'none';
      banner.classList.add('is-dismissed');
      localStorage.setItem('fnx_banner_dismissed_v2', 'true');
      const d = new Date();
      d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
      document.cookie = 'fnx_announcement_dismissed=1; path=/; expires=' + d.toUTCString() + '; SameSite=Lax';
    });
  }

  // ── 2. SINGLE SEARCH OVERLAY WITH FOCUS TRAP & ESC CLOSE ──
  function initSearchOverlay() {
    const searchToggle = document.getElementById('search-toggle');
    const searchModal = document.getElementById('fnx-search-modal');
    const searchClose = document.getElementById('search-modal-close');
    const searchInput = document.getElementById('search-modal-input');

    if (!searchToggle || !searchModal) return;

    function openSearch() {
      searchModal.classList.add('is-open');
      searchModal.setAttribute('aria-hidden', 'false');
      searchToggle.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
      if (searchInput) {
        setTimeout(() => searchInput.focus(), 50);
      }
    }

    function closeSearch() {
      searchModal.classList.remove('is-open');
      searchModal.setAttribute('aria-hidden', 'true');
      searchToggle.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
      searchToggle.focus();
    }

    searchToggle.addEventListener('click', function (e) {
      e.preventDefault();
      openSearch();
    });

    const mobileSearchTrigger = document.getElementById('mobile-drawer-search-trigger');
    if (mobileSearchTrigger) {
      mobileSearchTrigger.addEventListener('click', function (e) {
        e.preventDefault();
        const mobileNav = document.getElementById('mobile-nav');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const mobileToggle = document.getElementById('mobile-toggle');
        if (mobileNav) mobileNav.classList.remove('open', 'is-open');
        if (mobileOverlay) mobileOverlay.classList.remove('open', 'is-active', 'active');
        if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        setTimeout(() => openSearch(), 80);
      });
    }

    if (searchClose) {
      searchClose.addEventListener('click', function (e) {
        e.preventDefault();
        closeSearch();
      });
    }

    // Close on background click
    searchModal.addEventListener('click', function (e) {
      if (e.target === searchModal) {
        closeSearch();
      }
    });

    // Keyboard navigation: Escape key & focus trap
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && searchModal.classList.contains('is-open')) {
        closeSearch();
      }

      if (e.key === 'Tab' && searchModal.classList.contains('is-open')) {
        const focusable = searchModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusable.length === 0) return;
        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    });
  }

  // ── 4. TOOLS "COMING SOON" DEDICATED WAITLIST FORM ──
  function initToolsWaitlist() {
    const waitlistForms = document.querySelectorAll('.tools-waitlist-form');
    waitlistForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        const btn = form.querySelector('button[type="submit"]');
        const input = form.querySelector('input[type="email"]');
        if (btn && input && input.value) {
          btn.disabled = true;
          const origText = btn.innerHTML;
          btn.innerHTML = 'Adding to Waitlist…';
          // Fall through to normal WP admin-post.php or handle gracefully
        }
      });
    });
  }

  // ── 5. CONTACT FORM STATES (Loading, Success, Inline validation) ──
  function initContactForm() {
    const contactForms = document.querySelectorAll('.hp-lead-contact-form-v2');
    contactForms.forEach(form => {
      form.addEventListener('submit', function (e) {
        const btn = form.querySelector('.form-submit-btn-v2');
        if (btn && form.checkValidity()) {
          btn.disabled = true;
          btn.innerHTML = 'Sending Enquiry…';
        }
      });
    });
  }

  // ── 6. READING PROGRESS BAR FALLBACK ──
  function initReadingProgress() {
    const progressBar = document.querySelector('.fnx-reading-progress-bar');
    if (!progressBar) return;

    // Check if CSS scroll-driven animation is supported
    if (CSS.supports && CSS.supports('animation-timeline', 'scroll()')) {
      return; // Handled natively by modern CSS!
    }

    // JS Fallback for browsers without animation-timeline: scroll()
    window.addEventListener('scroll', function () {
      const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      if (docHeight <= 0) return;
      const scrollPos = window.scrollY || window.pageYOffset;
      const progress = Math.min(100, Math.max(0, (scrollPos / docHeight) * 100));
      progressBar.style.width = progress + '%';
    }, { passive: true });
  }

  // ── 7. LIGHT / DARK THEME TOGGLE & PERSISTENCE ──
  function initThemeToggle() {
    const desktopToggle = document.getElementById('theme-toggle');
    const mobileToggle = document.getElementById('mobile-theme-toggle');
    const mediaDark = window.matchMedia('(prefers-color-scheme: dark)');

    function getCurrentTheme() {
      try {
        const stored = localStorage.getItem('fnx_theme');
        if (stored === 'dark' || stored === 'light') return stored;
      } catch (e) {}
      return mediaDark.matches ? 'dark' : 'light';
    }

    function applyTheme(theme, save) {
      const isDark = theme === 'dark';
      document.documentElement.setAttribute('data-theme', theme);
      document.documentElement.classList.toggle('dark-mode', isDark);
      document.documentElement.classList.toggle('light-mode', !isDark);
      document.documentElement.style.colorScheme = theme;

      if (document.body) {
        document.body.setAttribute('data-theme', theme);
        document.body.classList.toggle('dark-mode', isDark);
        document.body.classList.toggle('light-mode', !isDark);
      }

      const mobileState = document.querySelector('.mobile-theme-state');
      if (mobileState) {
        mobileState.textContent = isDark ? 'Dark' : 'Light';
      }

      if (desktopToggle) {
        desktopToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        desktopToggle.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
      }

      if (save) {
        try {
          localStorage.setItem('fnx_theme', theme);
        } catch (e) {}
      }
    }

    function toggleTheme() {
      const current = getCurrentTheme();
      const next = current === 'dark' ? 'light' : 'dark';
      applyTheme(next, true);
    }

    if (desktopToggle) {
      desktopToggle.addEventListener('click', function (e) {
        e.preventDefault();
        toggleTheme();
      });
    }

    if (mobileToggle) {
      mobileToggle.addEventListener('click', function (e) {
        e.preventDefault();
        toggleTheme();
      });
    }

    // Initialize state
    applyTheme(getCurrentTheme(), false);

    // Listen for OS theme changes if user has not explicitly set a preference
    const handleMediaChange = (e) => {
      try {
        if (!localStorage.getItem('fnx_theme')) {
          applyTheme(e.matches ? 'dark' : 'light', false);
        }
      } catch (err) {}
    };

    if (mediaDark.addEventListener) {
      mediaDark.addEventListener('change', handleMediaChange);
    } else if (mediaDark.addListener) {
      mediaDark.addListener(handleMediaChange);
    }
  }

  // ── 10. BACK TO TOP BUTTON ──
  function initBackToTop() {
    const btn = document.getElementById('fnx-back-to-top');
    if (!btn) return;

    let ticking = false;
    window.addEventListener('scroll', function () {
      if (!ticking) {
        window.requestAnimationFrame(function () {
          if (window.scrollY > 400) {
            btn.classList.add('is-visible');
          } else {
            btn.classList.remove('is-visible');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── 8. MOBILE COLLAPSIBLE TOC POPULATION ──
  function initMobileTOC() {
    const mobileList = document.getElementById('toc-mobile-list');
    const article = document.getElementById('post-content');
    if (!mobileList || !article) return;

    const contentBody = article.querySelector('.article-body-content, .entry-content, .post-content') || article;
    const allHeadings = Array.from(contentBody.querySelectorAll('h2, h3'));
    const headings = allHeadings.filter(h => {
      return !h.closest('.fnx-faq, .fnx-feature-cards, .fnx-timeline, .fnx-comparison-wrap, .fnx-cta-box, .post-author-card, .post-suggested, .fnx-article-newsletter-box, .post-comments-wrap, .fnx-mobile-toc');
    });
    if (!headings.length) {
      const mobileToc = document.getElementById('fnx-mobile-toc');
      if (mobileToc) mobileToc.style.display = 'none';
      return;
    }

    mobileList.innerHTML = '';
    headings.forEach((h, idx) => {
      if (!h.id) {
        h.id = 'toc-' + h.textContent.trim().toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').slice(0, 50) + '-' + idx;
      }
      const li = document.createElement('li');
      li.className = 'toc-' + h.tagName.toLowerCase();
      const a = document.createElement('a');
      a.href = '#' + h.id;
      a.textContent = h.textContent.trim();
      a.addEventListener('click', (e) => {
        e.preventDefault();
        const details = document.getElementById('fnx-mobile-toc');
        if (details) details.open = false;
        const target = document.getElementById(h.id);
        if (target) {
          const offset = 88;
          const top = target.getBoundingClientRect().top + window.scrollY - offset;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      });
      li.appendChild(a);
      mobileList.appendChild(li);
    });
  }

  // ── 9. OFF-CANVAS MOBILE DRAWER TOGGLE ──
  function initMobileNav() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileClose = document.getElementById('mobile-close');
    const mobileOverlay = document.getElementById('mobile-overlay');

    if (!mobileToggle || !mobileNav) return;

    function openNav() {
      mobileNav.classList.add('open', 'is-open');
      mobileNav.setAttribute('aria-hidden', 'false');
      mobileToggle.setAttribute('aria-expanded', 'true');
      if (mobileOverlay) {
        mobileOverlay.classList.add('open', 'is-active');
        mobileOverlay.setAttribute('aria-hidden', 'false');
      }
      document.body.style.overflow = 'hidden';
    }

    function closeNav() {
      mobileNav.classList.remove('open', 'is-open');
      mobileNav.setAttribute('aria-hidden', 'true');
      mobileToggle.setAttribute('aria-expanded', 'false');
      if (mobileOverlay) {
        mobileOverlay.classList.remove('open', 'is-active');
        mobileOverlay.setAttribute('aria-hidden', 'true');
      }
      document.body.style.overflow = '';
      mobileToggle.focus();
    }

    mobileToggle.addEventListener('click', function (e) {
      e.preventDefault();
      openNav();
    });

    if (mobileClose) {
      mobileClose.addEventListener('click', function (e) {
        e.preventDefault();
        closeNav();
      });
    }

    if (mobileOverlay) {
      mobileOverlay.addEventListener('click', function (e) {
        e.preventDefault();
        closeNav();
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && (mobileNav.classList.contains('open') || mobileNav.classList.contains('is-open'))) {
        closeNav();
      }
    });

    // Close on navigation link click
    const navLinks = mobileNav.querySelectorAll('.mobile-menu-link:not(.mobile-categories-summary), .mobile-sublink, .mobile-cta-btn');
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        closeNav();
      });
    });
  }

  // DOMContentLoaded initialization
  function initAll() {
    initTopBanner();
    initSearchOverlay();
    initToolsWaitlist();
    initContactForm();
    initReadingProgress();
    initThemeToggle();
    initMobileTOC();
    initMobileNav();
    initBackToTop();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
