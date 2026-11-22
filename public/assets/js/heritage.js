(() => {
  const menu = document.querySelector('.menu-toggle');
  const nav = document.getElementById('main-nav');
  menu?.addEventListener('click', () => {
    const open = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(open));
    menu.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    nav?.classList.toggle('open', open);
  });

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
