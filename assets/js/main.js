(function(){
  const btn = document.querySelector('[data-menu-button]');
  const nav = document.querySelector('[data-nav]');
  if(!btn || !nav) return;

  btn.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    btn.setAttribute('aria-expanded', String(isOpen));
  });
})();
