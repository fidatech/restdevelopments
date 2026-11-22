(() => {
  const menu = document.querySelector('.menu-toggle');
  const nav = document.getElementById('main-nav');
  menu?.addEventListener('click', () => {
    const open = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    nav?.classList.toggle('open', open);
  });


  const carousel = document.querySelector('[data-hero-carousel]');
  const slides = [...(carousel?.querySelectorAll('[data-hero-slide]') || [])];
  if (slides.length > 1) {
    const pages = [...carousel.querySelectorAll('[data-hero-to]')];
    const play = carousel.querySelector('[data-hero-play]');
    let current = 0;
    let paused = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const load = slide => {
      const source = slide.querySelector('source[data-srcset]');
      if (source) { source.srcset = source.dataset.srcset; source.removeAttribute('data-srcset'); }
      const img = slide.querySelector('img[data-src]');
      if (img) { img.src = img.dataset.src; img.removeAttribute('data-src'); }
    };
    const show = index => {
      const next = (index + slides.length) % slides.length;
      load(slides[next]);
      slides[current].hidden = true;
      slides[current].classList.remove('is-active');
      slides[next].hidden = false;
      slides[next].classList.add('is-active');
      pages.forEach((button, i) => {
        if (i === next) button.setAttribute('aria-current', 'true');
        else button.removeAttribute('aria-current');
      });
      current = next;
    };
    const setPaused = value => {
      paused = value;
      play.setAttribute('aria-pressed', String(value));
      play.setAttribute('aria-label', value ? 'Play slideshow' : 'Pause slideshow');
      play.textContent = value ? '▶' : 'Ⅱ';
    };
    setPaused(paused);
    carousel.querySelector('[data-hero-prev]')?.addEventListener('click', () => { setPaused(true); show(current - 1); });
    carousel.querySelector('[data-hero-next]')?.addEventListener('click', () => { setPaused(true); show(current + 1); });
    pages.forEach((button, i) => button.addEventListener('click', () => { setPaused(true); show(i); }));
    play?.addEventListener('click', () => setPaused(!paused));
    carousel.addEventListener('mouseenter', () => { if (!paused) setPaused(true); });
    carousel.addEventListener('focusin', event => { if (event.target !== play && !paused) setPaused(true); });
    let touchX = null;
    carousel.addEventListener('touchstart', event => { touchX = event.changedTouches[0]?.clientX ?? null; }, { passive: true });
    carousel.addEventListener('touchend', event => {
      if (touchX === null) return;
      const distance = (event.changedTouches[0]?.clientX ?? touchX) - touchX;
      touchX = null;
      if (Math.abs(distance) > 55) { setPaused(true); show(current + (distance < 0 ? 1 : -1)); }
    }, { passive: true });
    window.setInterval(() => {
      if (!paused && !document.hidden && !document.body.classList.contains('modal-open')) show(current + 1);
    }, 7000);
  }

  const popup = document.getElementById('socialRedirectModal');
  if (!popup) return;
  let previousFocus;
  const dismiss = () => {
    popup.hidden = true;
    document.body.classList.remove('modal-open');
    previousFocus?.focus();
  };
  popup.querySelectorAll('[data-welcome-dismiss]').forEach(button => button.addEventListener('click', dismiss));
  popup.addEventListener('click', event => { if (event.target === popup) dismiss(); });
  popup.addEventListener('keydown', event => {
    if (event.key === 'Escape') dismiss();
    if (event.key !== 'Tab') return;
    const focusables = [...popup.querySelectorAll('button, a[href]')];
    const first = focusables[0], last = focusables[focusables.length - 1];
    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
  });
  try {
    if (sessionStorage.getItem('restWelcomeShown')) return;
    sessionStorage.setItem('restWelcomeShown', 'yes');
  } catch (_) { return; }
  previousFocus = document.activeElement;
  popup.hidden = false;
  document.body.classList.add('modal-open');
  popup.querySelector('.welcome-close')?.focus();
})();
