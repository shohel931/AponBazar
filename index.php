<?php 
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AponBazar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/slider.css">
</head>
<body>

<?php include 'includs/header.php'; ?>

<!-- 🔹 Dynamic Slider Section -->
<section id="slider" class="slider" aria-label="Homepage slider">
  <button class="slider__btn slider__btn--prev" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>

  <div class="slider__track">
    <?php
    $sliders = $conn->query("SELECT * FROM sliders ORDER BY id DESC");
    while ($slide = $sliders->fetch_assoc()):
    ?>
      <div class="slide">
        <a href="<?= $slide['link'] ?: '#' ?>">
          <img src="uploads/<?= htmlspecialchars($slide['image']) ?>" alt="Slide">
        </a>
      </div>
    <?php endwhile; ?>
  </div>

  <button class="slider__btn slider__btn--next" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
  <div class="slider__dots"></div>
</section>

<?php include 'includs/footer.php'; ?>

<script src="js/slider.js"></script>
<script src="js/header.js"></script>
</body>
</html>
