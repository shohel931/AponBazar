const track = document.querySelector('.slider__track');
const slides = Array.from(track.children);
const nextBtn = document.querySelector('.slider__btn--next');
const prevBtn = document.querySelector('.slider__btn--prev');
const dotsNav = document.querySelector('.slider__dots');

// Create dots
slides.forEach((_, i) => {
  const dot = document.createElement('button');
  if (i === 0) dot.classList.add('active');
  dotsNav.appendChild(dot);
});

const dots = Array.from(dotsNav.children);
let currentIndex = 0;

function updateSlide(index) {
  track.style.transform = `translateX(-${index * 100}%)`;
  dots.forEach(dot => dot.classList.remove('active'));
  dots[index].classList.add('active');
}

nextBtn.addEventListener('click', () => {
  currentIndex = (currentIndex + 1) % slides.length;
  updateSlide(currentIndex);
});

prevBtn.addEventListener('click', () => {
  currentIndex = (currentIndex - 1 + slides.length) % slides.length;
  updateSlide(currentIndex);
});

dots.forEach((dot, i) => {
  dot.addEventListener('click', () => {
    currentIndex = i;
    updateSlide(currentIndex);
  });
});

// Auto-slide every 5 seconds
setInterval(() => {
  currentIndex = (currentIndex + 1) % slides.length;
  updateSlide(currentIndex);
}, 5000);
