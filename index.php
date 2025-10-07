<?php 
include 'db.php';
session_start();






?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <title>AponBazar</title>
</head>
<body>


<?php include 'includs/header.php'; ?>



<section id="slider" class="slider" aria-label="Homepage slider">
  <button class="slider__btn slider__btn--prev" aria-label="Previous slide">&larr;</button>

  <div class="slider__track">
    <div class="slide" role="group" aria-roledescription="slide" aria-label="Slide 1">
      <a href="#"><img src="./img/slider.png" alt="Slide 1"></a>
    </div>
    <div class="slide" role="group" aria-roledescription="slide" aria-label="Slide 2">
      <a href="#"><img src="./img/slider.png" alt="Slide 2"></a>
    </div>
    <div class="slide" role="group" aria-roledescription="slide" aria-label="Slide 3">
      <a href="#"><img src="./img/slider.png" alt="Slide 3"></a>
    </div>
    <div class="slide" role="group" aria-roledescription="slide" aria-label="Slide 4">
      <a href="#"><img src="./img/slider.png" alt="Slide 4"></a>
    </div>
  </div>

  <button class="slider__btn slider__btn--next" aria-label="Next slide">&rarr;</button>

  <div class="slider__dots" aria-hidden="false"></div>
</section>





















<?php include 'includs/footer.php'; ?>

    <script src="js/script.js"></script>
    <script src="js/header.js"></script>
</body>
</html> 