(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const rtl = document.documentElement.dir === 'rtl';

  /* Header: transparent over the hero, white once the page scrolls */
  const header = document.querySelector('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('is-solid', window.scrollY > 40);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Mobile menu */
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById('main-nav');
  const setMenu = open => {
    if (!toggle || !nav) return;
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
    nav.classList.toggle('open', open);
    document.body.classList.toggle('menu-open', open);
  };
  toggle?.addEventListener('click', () => setMenu(toggle.getAttribute('aria-expanded') !== 'true'));
  nav?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));

  /* Language menu: close on outside click or Escape */
  const lang = document.querySelector('.lang');
  document.addEventListener('click', e => { if (lang?.open && !lang.contains(e.target)) lang.open = false; });
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (lang?.open) lang.open = false;
    if (toggle?.getAttribute('aria-expanded') === 'true') { setMenu(false); toggle.focus(); }
  });

  /* Home hero slides */
  const hero = document.querySelector('[data-hero]');
  const slides = [...(hero?.querySelectorAll('[data-hero-slide]') || [])];
  if (slides.length > 1) {
    const copies = [...hero.querySelectorAll('[data-hero-copy]')];
    const dots = [...hero.querySelectorAll('[data-hero-to]')];
    let current = 0, timer = null, paused = reduceMotion;
    const load = el => {
      el.querySelectorAll('[data-srcset]').forEach(s => { s.srcset = s.dataset.srcset; s.removeAttribute('data-srcset'); });
      el.querySelectorAll('[data-src]').forEach(i => { i.src = i.dataset.src; i.removeAttribute('data-src'); });
    };
    const schedule = () => {
      clearTimeout(timer);
      if (paused) return;
      timer = setTimeout(() => {
        if (document.hidden || document.body.classList.contains('modal-open')) return schedule();
        show(current + 1);
      }, 7000);
    };
    const show = i => {
      const next = (i + slides.length) % slides.length;
      if (next === current) return;
      load(slides[next]);
      [slides, copies].forEach(list => { list[current]?.classList.remove('is-active'); list[next]?.classList.add('is-active'); });
      dots.forEach((d, n) => n === next ? d.setAttribute('aria-current', 'true') : d.removeAttribute('aria-current'));
      current = next;
      schedule();
    };
    dots.forEach((d, n) => d.addEventListener('click', () => { paused = true; clearTimeout(timer); show(n); }));
    hero.addEventListener('focusin', e => { if (e.target.closest('.search-bar')) { paused = true; clearTimeout(timer); } });
    let touchX = null;
    hero.addEventListener('touchstart', e => { touchX = e.changedTouches[0]?.clientX ?? null; }, { passive: true });
    hero.addEventListener('touchend', e => {
      if (touchX === null) return;
      const dx = (e.changedTouches[0]?.clientX ?? touchX) - touchX; touchX = null;
      if (Math.abs(dx) > 50) show(current + ((dx < 0) !== rtl ? 1 : -1));
    }, { passive: true });
    window.addEventListener('load', () => setTimeout(() => load(slides[1]), 1500));
    schedule();
  }

  /* New launches carousel */
  const launches = document.querySelector('[data-launches]');
  const items = [...(launches?.querySelectorAll('[data-launch]') || [])];
  if (items.length > 1) {
    const count = launches.querySelector('[data-launch-count]');
    let at = 0;
    const go = i => {
      items[at].classList.remove('is-active');
      at = (i + items.length) % items.length;
      items[at].classList.add('is-active');
      if (count) count.textContent = String(at + 1).padStart(2, '0') + ' / ' + String(items.length).padStart(2, '0');
    };
    launches.querySelector('[data-launch-prev]')?.addEventListener('click', () => go(at - 1));
    launches.querySelector('[data-launch-next]')?.addEventListener('click', () => go(at + 1));
  }

  /* Welcome popup: once per browser session */
  const popup = document.getElementById('socialRedirectModal');
  if (!popup) return;
  let previousFocus = null;
  const close = () => { popup.hidden = true; document.body.classList.remove('modal-open'); previousFocus?.focus?.(); };
  popup.querySelectorAll('[data-welcome-dismiss]').forEach(b => b.addEventListener('click', close));
  popup.addEventListener('click', e => { if (e.target === popup) close(); });
  popup.addEventListener('keydown', e => {
    if (e.key === 'Escape') { close(); return; }
    if (e.key !== 'Tab') return;
    const f = [...popup.querySelectorAll('button, a[href]')];
    if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
    else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
  });
  const forced = new URLSearchParams(location.search).has('welcome');
  try {
    if (!forced && sessionStorage.getItem('restWelcomeShown')) return;
    sessionStorage.setItem('restWelcomeShown', '1');
  } catch (_) { if (!forced) return; }
  previousFocus = document.activeElement;
  popup.hidden = false;
  document.body.classList.add('modal-open');
  popup.querySelector('.welcome-panel')?.focus();
})();
