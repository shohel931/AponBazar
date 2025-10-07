<?php 
include 'db.php';
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/shop.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <title>Shop - AponBazar</title>
</head>
<body>
   
    <?php include 'includs/header.php'; ?>



<br><br><br><br>

    
<section class="shop_page">
    <h2>All Products</h2>
    <div class="shop_container">
        <div class="filter">
            <h2>Filters</h2>
            <div class="filter_section">
                <h3>Category</h3>
                <ul>
                    <li><a href="#">Category 1</a></li>
                    <li><a href="#">Category 2</a></li>
                    <li><a href="#">Category 3</a></li>
                    <li><a href="#">Category 4</a></li>
                </ul>
            </div>
            <div class="filter_section">
                <h3>Price Range</h3>
                <input type="range" min="0" max="100" value="50" class="price_range">
                <span>$0 - $100</span>
            </div>
            <div class="filter_section">
                <h3>Brand</h3>
                <ul>
                    <li><a href="#">Brand A</a></li>
                    <li><a href="#">Brand B</a></li>
                    <li><a href="#">Brand C</a></li>
                    <li><a href="#">Brand D</a></li>
                </ul>
            </div>
        </div>
        <div class="products">
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/sm.png" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/sm.png" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/sm.png" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/sm.png" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/sm.png" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            <div class="product_card">
                <div class="product_img">
                <a href="#"><img src="./img/apple.jpg" alt="Product 1"></a>
                <button title="add to favorite" class="wishlist"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="pname">
                <a href="#"><h3>Smart wacth model 47598 hello world</h3></a>
                <div class="ratting">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
                <p>৳150.00</p>
                <button><i class="fa-solid fa-cart-shopping"></i> Add to Cart</button>
                </div>
            </div>
            
        </div>
    </div>
</section>










<?php include 'includs/footer.php'; ?>
<script src="js/header.js"></script>
<script src="js/script.js"></script>
</body>
</html>