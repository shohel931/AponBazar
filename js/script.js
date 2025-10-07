// Vanilla JS slider with autoplay, controls, dots, keyboard and touch support
(function () {
  const slider = document.querySelector('.slider');
  const track = slider.querySelector('.slider__track');
  const slides = Array.from(track.querySelectorAll('.slide'));
  const prevBtn = slider.querySelector('.slider__btn--prev');
  const nextBtn = slider.querySelector('.slider__btn--next');
  const dotsWrap = slider.querySelector('.slider__dots');

  let current = 0;
  const slideCount = slides.length;
  const intervalMs = 4000;
  let autoplay = true;
  let timerId = null;

  // Build dots
  slides.forEach((_, i) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.setAttribute('aria-label', 'Go to slide ' + (i + 1));
    if (i === 0) btn.setAttribute('aria-current', 'true');
    dotsWrap.appendChild(btn);
    btn.addEventListener('click', () => goTo(i, true));
  });

  const dots = Array.from(dotsWrap.children);

  function updateUI() {
    // move track
    const offset = -current * 100;
    track.style.transform = `translateX(${offset}%)`;

    // update aria-current on dots
    dots.forEach((d, i) => {
      if (i === current) d.setAttribute('aria-current', 'true');
      else d.removeAttribute('aria-current');
    });
  }

  function goTo(index, userAction = false) {
    current = (index + slideCount) % slideCount;
    updateUI();
    if (userAction) restartAutoplay();
  }

  function next() { goTo(current + 1, false); }
  function prev() { goTo(current - 1, false); }

  // Autoplay handlers
  function startAutoplay() {
    if (!autoplay) return;
    stopAutoplay();
    timerId = setInterval(() => {
      next();
    }, intervalMs);
  }
  function stopAutoplay() {
    if (timerId) {
      clearInterval(timerId);
      timerId = null;
    }
  }
  function restartAutoplay() {
    stopAutoplay();
    startAutoplay();
  }

  // Event listeners
  nextBtn.addEventListener('click', () => { next(); restartAutoplay(); });
  prevBtn.addEventListener('click', () => { prev(); restartAutoplay(); });

  // Pause on hover/focus
  slider.addEventListener('mouseenter', stopAutoplay);
  slider.addEventListener('mouseleave', startAutoplay);
  slider.addEventListener('focusin', stopAutoplay);
  slider.addEventListener('focusout', startAutoplay);

  // keyboard support
  document.addEventListener('keydown', (e) => {
    if (!slider.contains(document.activeElement) && document.activeElement.tagName !== 'BODY') return;
    if (e.key === 'ArrowRight') { next(); restartAutoplay(); }
    if (e.key === 'ArrowLeft')  { prev(); restartAutoplay(); }
  });

  // touch / swipe support
  let startX = 0;
  let isTouch = false;
  slider.addEventListener('touchstart', (e) => {
    stopAutoplay();
    isTouch = true;
    startX = e.touches[0].clientX;
  }, {passive: true});
  slider.addEventListener('touchmove', (e) => {
    if (!isTouch) return;
    const dx = e.touches[0].clientX - startX;
    // small threshold for visual feedback (optional)
  }, {passive: true});
  slider.addEventListener('touchend', (e) => {
    if (!isTouch) return;
    const endX = (e.changedTouches && e.changedTouches[0].clientX) || 0;
    const dx = endX - startX;
    const threshold = 50; // swipe threshold
    if (dx > threshold) { prev(); }
    else if (dx < -threshold) { next(); }
    isTouch = false;
    restartAutoplay();
  });

  // make images lazy-load (if supported)
  slides.forEach(s => {
    const img = s.querySelector('img');
    if (img && 'loading' in HTMLImageElement.prototype) {
      img.setAttribute('loading', 'lazy');
    }
  });

  // init
  updateUI();
  startAutoplay();

  // expose small API (optional)
  slider._sliderApi = { next, prev, goTo, stopAutoplay, startAutoplay };
})();




