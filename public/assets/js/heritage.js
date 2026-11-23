(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* Header: hairline once the page scrolls */
  const header = document.querySelector('[data-header]');
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Mobile menu */
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.getElementById('main-nav');
  const setMenu = open => {
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
    nav.classList.toggle('open', open);
    document.body.classList.toggle('modal-open', open);
  };
  toggle?.addEventListener('click', () => setMenu(toggle.getAttribute('aria-expanded') !== 'true'));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && toggle?.getAttribute('aria-expanded') === 'true') { setMenu(false); toggle.focus(); }
  });
  window.matchMedia('(min-width: 1181px)').addEventListener?.('change', e => { if (e.matches && nav?.classList.contains('open')) setMenu(false); });

  /* Home hero slider */
  const hero = document.querySelector('[data-hero]');
  const slides = [...(hero?.querySelectorAll('[data-hero-slide]') || [])];
  if (slides.length > 1) {
    const tabs = [...hero.querySelectorAll('[data-hero-to]')];
    const pause = hero.querySelector('[data-hero-pause]');
    const delay = 7000;
    hero.style.setProperty('--hero-delay', `${delay}ms`);
    let current = 0;
    let timer = null;
    let paused = reduceMotion;

    const load = slide => {
      slide.querySelectorAll('[data-srcset]').forEach(el => { el.srcset = el.dataset.srcset; el.removeAttribute('data-srcset'); });
      slide.querySelectorAll('[data-src]').forEach(el => { el.src = el.dataset.src; el.removeAttribute('data-src'); });
    };
    const show = index => {
      const next = (index + slides.length) % slides.length;
      if (next === current) return;
      load(slides[next]);
      slides[current].classList.remove('is-active');
      slides[current].setAttribute('aria-hidden', 'true');
      slides[next].classList.add('is-active');
      slides[next].removeAttribute('aria-hidden');
      tabs.forEach((tab, i) => {
        if (i === next) tab.setAttribute('aria-current', 'true'); else tab.removeAttribute('aria-current');
      });
      current = next;
      schedule();
    };
    const schedule = () => {
      clearTimeout(timer);
      if (!paused) timer = setTimeout(() => {
        if (document.hidden || document.body.classList.contains('modal-open')) return schedule();
        show(current + 1);
      }, delay);
    };
    const setPaused = value => {
      paused = value;
      hero.classList.toggle('is-paused', value);
      if (pause) {
        pause.setAttribute('aria-pressed', String(value));
        pause.setAttribute('aria-label', value ? pause.dataset.playLabel : pause.dataset.pauseLabel);
      }
      schedule();
    };

    // Preload the second slide once the page has settled
    window.addEventListener('load', () => setTimeout(() => load(slides[1]), 1200));
    tabs.forEach((tab, i) => tab.addEventListener('click', () => { show(i); }));
    pause?.addEventListener('click', () => setPaused(!paused));
    hero.addEventListener('focusin', event => { if (event.target !== pause && !paused) setPaused(true); });

    let touchX = null;
    hero.addEventListener('touchstart', event => { touchX = event.changedTouches[0]?.clientX ?? null; }, { passive: true });
    hero.addEventListener('touchend', event => {
      if (touchX === null) return;
      const distance = (event.changedTouches[0]?.clientX ?? touchX) - touchX;
      touchX = null;
      const rtl = document.documentElement.dir === 'rtl';
      if (Math.abs(distance) > 50) show(current + ((distance < 0) !== rtl ? 1 : -1));
    }, { passive: true });

    setPaused(paused);
  }

  /* Welcome popup — once per browser session */
  const popup = document.getElementById('socialRedirectModal');
  if (!popup) return;
  let previousFocus = null;
  const close = () => {
    popup.hidden = true;
    document.body.classList.remove('modal-open');
    previousFocus?.focus?.();
  };
  popup.querySelectorAll('[data-welcome-dismiss]').forEach(btn => btn.addEventListener('click', close));
  popup.addEventListener('click', event => { if (event.target === popup) close(); });
  popup.addEventListener('keydown', event => {
    if (event.key === 'Escape') { close(); return; }
    if (event.key !== 'Tab') return;
    const focusables = [...popup.querySelectorAll('button, a[href]')];
    const first = focusables[0];
    const last = focusables[focusables.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
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
