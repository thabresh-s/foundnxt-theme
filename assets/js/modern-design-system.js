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

  // ── 1. WHAT'S NEW TOP BANNER DISMISSAL ──
  function initTopBanner() {
    const banner = document.getElementById('fnx-top-banner');
    const closeBtn = document.getElementById('top-banner-close');
    if (!banner || !closeBtn) return;

    if (localStorage.getItem('fnx_banner_dismissed_v2') === 'true') {
      banner.classList.add('is-dismissed');
      return;
    }

    closeBtn.addEventListener('click', function () {
      banner.classList.add('is-dismissed');
      localStorage.setItem('fnx_banner_dismissed_v2', 'true');
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

  // ── 3. COOKIE CONSENT BANNER (Accept, Decline, Preferences) ──
  function initCookieConsent() {
    const banner = document.getElementById('fnx-cookie-consent');
    if (!banner) return;

    const consentStatus = localStorage.getItem('fnx_cookie_consent');
    if (consentStatus) {
      banner.classList.add('is-hidden');
      return;
    }

    const acceptBtn = document.getElementById('cookie-btn-accept');
    const declineBtn = document.getElementById('cookie-btn-decline');
    const prefBtn = document.getElementById('cookie-btn-pref');

    if (acceptBtn) {
      acceptBtn.addEventListener('click', function () {
        localStorage.setItem('fnx_cookie_consent', 'accepted');
        banner.classList.add('is-hidden');
      });
    }

    if (declineBtn) {
      declineBtn.addEventListener('click', function () {
        localStorage.setItem('fnx_cookie_consent', 'declined');
        banner.classList.add('is-hidden');
      });
    }

    if (prefBtn) {
      prefBtn.addEventListener('click', function (e) {
        e.preventDefault();
        // Redirect or open privacy policy preferences section
        window.location.href = prefBtn.getAttribute('href') || '/privacy-policy/';
      });
    }
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

  // ── 7. LIGHT / DARK THEME TOGGLE WITH COLOR-SCHEME SYNC ──
  function initThemeToggle() {
    const toggles = document.querySelectorAll('#dark-toggle, .dark-toggle');
    if (!toggles.length) return;

    function applyTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      document.documentElement.classList.remove('light-mode', 'dark-mode');
      document.documentElement.classList.add(theme === 'dark' ? 'dark-mode' : 'light-mode');
      document.documentElement.style.colorScheme = theme;
      localStorage.setItem('fnx_theme', theme);
    }

    toggles.forEach(toggle => {
      toggle.addEventListener('click', function () {
        const current = document.documentElement.getAttribute('data-theme') ||
          (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
      });
    });
  }

  // ── 8. MOBILE COLLAPSIBLE TOC POPULATION ──
  function initMobileTOC() {
    const mobileList = document.getElementById('toc-mobile-list');
    const article = document.getElementById('post-content');
    if (!mobileList || !article) return;

    const headings = article.querySelectorAll('.article-body-content h2, .article-body-content h3, .entry-content h2, .entry-content h3');
    if (!headings.length) {
      const mobileToc = document.getElementById('fnx-mobile-toc');
      if (mobileToc) mobileToc.style.display = 'none';
      return;
    }

    mobileList.innerHTML = '';
    headings.forEach((h, idx) => {
      if (!h.id) {
        h.id = 'fnx-heading-' + idx;
      }
      const li = document.createElement('li');
      const a = document.createElement('a');
      a.href = '#' + h.id;
      a.textContent = h.textContent.trim();
      a.addEventListener('click', () => {
        const details = document.getElementById('fnx-mobile-toc');
        if (details) details.open = false;
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
    initCookieConsent();
    initToolsWaitlist();
    initContactForm();
    initReadingProgress();
    initThemeToggle();
    initMobileTOC();
    initMobileNav();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
