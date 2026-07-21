/* BRALIM — site interactions */
(function () {
  'use strict';

  /* Mobile nav */
  var toggle = document.getElementById('navToggle');
  var mobileNav = document.getElementById('mobileNav');
  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      var open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      if (open) { mobileNav.hidden = true; }
      else { mobileNav.hidden = false; }
    });
    mobileNav.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') { mobileNav.hidden = true; toggle.setAttribute('aria-expanded', 'false'); }
    });
  }

  /* Sticky header shadow */
  var header = document.getElementById('siteHeader');
  if (header) {
    var onScroll = function () { header.classList.toggle('scrolled', window.scrollY > 8); };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Reveal on scroll */
  var reveals = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window && reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
      });
    }, { threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('in'); });
  }

  /* Animated counters */
  var counters = document.querySelectorAll('[data-count]');
  if ('IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        var el = en.target;
        cio.unobserve(el);
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        var suffix = el.getAttribute('data-suffix') || '';
        var start = 0, dur = 1400, t0 = performance.now();
        var step = function (now) {
          var p = Math.min((now - t0) / dur, 1);
          var eased = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.round(start + (target - start) * eased).toLocaleString() + suffix;
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
      });
    }, { threshold: 0.5 });
    counters.forEach(function (c) { cio.observe(c); });
  }

  /* Forms — client-side validation + friendly demo submit.
     NOTE: static hosting has no backend. To make these send real emails,
     set data-endpoint on the <form> to a Formspree/Getform URL (see README). */
  function handleForm(form) {
    var note = form.querySelector('[data-note]');
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!form.checkValidity()) { form.reportValidity(); return; }
      var endpoint = form.getAttribute('data-endpoint');
      if (note) { note.className = 'form-note'; note.textContent = 'Sending…'; }

      if (endpoint) {
        fetch(endpoint, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
          .then(function (r) {
            if (r.ok) { success(); } else { fail(); }
          }).catch(fail);
      } else {
        // Demo mode — no backend configured yet.
        setTimeout(success, 500);
      }

      function success() {
        if (note) { note.className = 'form-note ok'; note.textContent = form.getAttribute('data-success') || 'Thank you! We have received your message and will be in touch soon.'; }
        form.reset();
      }
      function fail() {
        if (note) { note.className = 'form-note err'; note.textContent = 'Something went wrong. Please email info@bralim.org.'; }
      }
    });
  }
  document.querySelectorAll('form[data-form]').forEach(handleForm);

  /* Cookie banner */
  var banner = document.getElementById('cookieBanner');
  if (banner) {
    var KEY = 'bralim-cookie-consent';
    try {
      if (!localStorage.getItem(KEY)) { banner.hidden = false; }
    } catch (e) { banner.hidden = false; }
    banner.addEventListener('click', function (e) {
      var choice = e.target.getAttribute('data-cookie');
      if (!choice) return;
      try { localStorage.setItem(KEY, choice); } catch (e2) {}
      banner.hidden = true;
      // If accepted, this is where analytics would be initialised.
    });
  }

  /* Footer year (in case JS-rendered) */
  document.querySelectorAll('[data-year]').forEach(function (el) { el.textContent = new Date().getFullYear(); });
})();
