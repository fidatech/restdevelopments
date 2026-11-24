(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const rtl = document.documentElement.dir === 'rtl';

  /* Header: transparent over the hero, white once the page scrolls */
  const header = document.querySelector('[data-header]');
  if (header) {
    const onScroll = () => { header.classList.toggle('is-solid', window.scrollY > 40); document.body.classList.toggle('past-hero', window.scrollY > 500); };
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

  /* Mega menus: hover on desktop, tap on touch and in the mobile menu */
  const megaItems = [...document.querySelectorAll('.has-mega')];
  const desktop = window.matchMedia('(min-width: 1181px)');
  const setMega = (item, open) => {
    megaItems.forEach(other => {
      const on = other === item && open;
      other.classList.toggle('is-open', on);
      other.querySelector('.nav-trigger')?.setAttribute('aria-expanded', String(on));
    });
    if (desktop.matches) header?.classList.toggle('mega-open', open);
  };
  megaItems.forEach(item => {
    let closeTimer;
    item.querySelector('.nav-trigger')?.addEventListener('click', () => setMega(item, !item.classList.contains('is-open')));
    item.addEventListener('mouseenter', () => { if (!desktop.matches) return; clearTimeout(closeTimer); setMega(item, true); });
    item.addEventListener('mouseleave', () => { if (!desktop.matches) return; closeTimer = setTimeout(() => { if (item.classList.contains('is-open')) setMega(item, false); }, 160); });
    item.addEventListener('focusout', e => { if (desktop.matches && !item.contains(e.relatedTarget)) setMega(item, false); });
  });
  document.addEventListener('click', e => { if (desktop.matches && !e.target.closest('.has-mega')) megaItems.forEach(i => i.classList.contains('is-open') && setMega(i, false)); });

  /* Search overlay */
  const search = document.getElementById('siteSearch');
  const openSearch = () => { if (!search) return; search.hidden = false; document.body.classList.add('modal-open'); search.querySelector('input')?.focus(); };
  const closeSearch = () => { if (!search || search.hidden) return; search.hidden = true; document.body.classList.remove('modal-open'); document.querySelector('[data-search-open]')?.focus(); };
  document.querySelectorAll('[data-search-open]').forEach(b => b.addEventListener('click', openSearch));
  search?.querySelector('[data-search-close]')?.addEventListener('click', closeSearch);
  search?.addEventListener('click', e => { if (e.target === search) closeSearch(); });

  /* Film modal (YouTube, loaded only when asked for) */
  const film = document.getElementById('filmModal');
  const closeFilm = () => { if (!film || film.hidden) return; film.hidden = true; film.querySelector('[data-film-player]').innerHTML = ''; document.body.classList.remove('modal-open'); };
  document.querySelectorAll('[data-film-open]').forEach(b => b.addEventListener('click', () => {
    if (!film) return;
    const f = document.createElement('iframe');
    f.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(b.dataset.film) + '?autoplay=1&rel=0';
    f.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
    f.title = b.textContent.trim();
    film.querySelector('[data-film-player]').replaceChildren(f);
    film.hidden = false; document.body.classList.add('modal-open');
    film.querySelector('[data-film-close]')?.focus();
  }));
  film?.querySelector('[data-film-close]')?.addEventListener('click', closeFilm);
  film?.addEventListener('click', e => { if (e.target === film) closeFilm(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') { closeSearch(); closeFilm(); megaItems.forEach(i => i.classList.contains('is-open') && setMega(i, false)); } });

  /* Launch rail: arrows scroll one card, counter follows */
  document.querySelectorAll('[data-rail]').forEach(rail => {
    const track = rail.querySelector('[data-rail-track]');
    const cards = [...(track?.querySelectorAll('[data-rail-item]') || [])];
    const count = rail.querySelector('[data-rail-count]');
    if (!track || !cards.length) return;
    const step = () => cards[0].getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 24);
    const dir = rtl ? -1 : 1;
    rail.querySelector('[data-rail-prev]')?.addEventListener('click', () => track.scrollBy({ left: -step() * dir, behavior: reduceMotion ? 'auto' : 'smooth' }));
    rail.querySelector('[data-rail-next]')?.addEventListener('click', () => track.scrollBy({ left: step() * dir, behavior: reduceMotion ? 'auto' : 'smooth' }));
    const update = () => {
      const i = Math.min(cards.length - 1, Math.round(Math.abs(track.scrollLeft) / step()));
      if (count) count.textContent = String(i + 1).padStart(2, '0') + ' / ' + String(cards.length).padStart(2, '0');
    };
    track.addEventListener('scroll', () => requestAnimationFrame(update), { passive: true });
  });

  /* Figures count up once, when they come into view */
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length && 'IntersectionObserver' in window && !reduceMotion) {
    const io = new IntersectionObserver(entries => entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      io.unobserve(entry.target);
      const el = entry.target, end = parseInt(el.dataset.count, 10) || 0, suffix = el.dataset.suffix || '';
      const t0 = performance.now();
      const tick = now => {
        const p = Math.min(1, (now - t0) / 1400), v = Math.round(end * (1 - Math.pow(1 - p, 3)));
        el.textContent = v + suffix;
        if (p < 1) requestAnimationFrame(tick);
      };
      requestAnimationFrame(tick);
    }), { threshold: .6 });
    counters.forEach(c => io.observe(c));
  }

  /* Project page: highlight the sub-nav section in view */
  const subLinks = [...document.querySelectorAll('[data-subnav] a[href^="#"]')];
  if (subLinks.length && 'IntersectionObserver' in window) {
    const map = new Map(subLinks.map(a => [document.querySelector(a.getAttribute('href')), a]).filter(([el]) => el));
    const so = new IntersectionObserver(entries => entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      subLinks.forEach(a => a.classList.remove('is-active'));
      map.get(entry.target)?.classList.add('is-active');
    }), { rootMargin: '-40% 0px -55% 0px' });
    map.forEach((_, el) => so.observe(el));
  }

  /* Project gallery: filter tabs, show all, lightbox */
  const gallery = document.querySelector('[data-gallery]');
  if (gallery) {
    const links = [...gallery.querySelectorAll('[data-lightbox]')];
    const tabs = [...document.querySelectorAll('[data-gallery-filter]')];
    const moreBtn = document.querySelector('[data-gallery-more]');
    tabs.forEach(tab => tab.addEventListener('click', () => {
      const type = tab.dataset.galleryFilter;
      tabs.forEach(t => { const on = t === tab; t.classList.toggle('is-active', on); t.setAttribute('aria-pressed', String(on)); });
      links.forEach(a => a.classList.toggle('is-hidden', type !== 'all' && a.dataset.type !== type));
      if (type !== 'all') { gallery.classList.add('is-open'); moreBtn?.parentElement && (moreBtn.parentElement.hidden = true); }
    }));
    moreBtn?.addEventListener('click', () => { gallery.classList.add('is-open'); moreBtn.parentElement.hidden = true; });

    const box = document.getElementById('lightbox');
    const img = box?.querySelector('[data-lightbox-img]');
    const count = box?.querySelector('[data-lightbox-count]');
    let list = [], at = 0, opener = null;
    const show = i => {
      at = (i + list.length) % list.length;
      img.src = list[at].href; img.alt = list[at].querySelector('img')?.alt || '';
      count.textContent = (at + 1) + ' / ' + list.length;
    };
    const close = () => { if (!box || box.hidden) return; box.hidden = true; document.body.classList.remove('modal-open'); opener?.focus(); };
    links.forEach(a => a.addEventListener('click', e => {
      if (!box) return;
      e.preventDefault();
      list = links.filter(l => !l.classList.contains('is-hidden'));
      opener = a; box.hidden = false; document.body.classList.add('modal-open');
      show(list.indexOf(a)); box.querySelector('[data-lightbox-close]')?.focus();
    }));
    box?.querySelector('[data-lightbox-close]')?.addEventListener('click', close);
    box?.querySelector('[data-lightbox-prev]')?.addEventListener('click', () => show(at - (rtl ? -1 : 1)));
    box?.querySelector('[data-lightbox-next]')?.addEventListener('click', () => show(at + (rtl ? -1 : 1)));
    box?.addEventListener('click', e => { if (e.target === box || e.target.tagName === 'FIGURE') close(); });
    document.addEventListener('keydown', e => {
      if (!box || box.hidden) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowRight') show(at + (rtl ? -1 : 1));
      if (e.key === 'ArrowLeft') show(at - (rtl ? -1 : 1));
    });
    let sx = null;
    box?.addEventListener('touchstart', e => { sx = e.touches[0].clientX; }, { passive: true });
    box?.addEventListener('touchend', e => { if (sx === null) return; const dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 40) show(at + (dx < 0 ? 1 : -1)); sx = null; });
  }

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
      slides.forEach((sl, n) => { const v = sl.querySelector('video'); if (!v) return; if (n === next) { v.play?.().catch(() => {}); } else v.pause?.(); });
      current = next;
      schedule();
    };
    dots.forEach((d, n) => d.addEventListener('click', () => { paused = true; clearTimeout(timer); show(n); }));
    hero.addEventListener('focusin', e => { if (e.target.closest('.search-bar, .finder')) { paused = true; clearTimeout(timer); } });
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
