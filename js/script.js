const slides = document.querySelectorAll('.slide');
let index = 0;
function showSlide(n){
  slides.forEach(slide => slide.classList.remove('active'));
  slides[n].classList.add('active');
}
document.querySelector('.next').onclick = () => {
  index = (index + 1) % slides.length;
  showSlide(index);
};
document.querySelector('.prev').onclick = () => {
  index = (index - 1 + slides.length) % slides.length;
  showSlide(index);
};
setInterval(() => {
  index = (index + 1) % slides.length;
  showSlide(index);
}, 4000);
showSlide(index);