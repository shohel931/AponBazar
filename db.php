<?php 
$host = 'localhost';
$dbname = 'aponbazar';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// ---------- USERS TABLE ----------
$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    number VARCHAR(20) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Customer', 'Admin') DEFAULT 'Customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createUsersTable);

// ---------- ADMINS TABLE ----------
$createAdminsTable = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createAdminsTable);

// Insert default admin if not exists
$checkAdmin = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($checkAdmin->num_rows == 0) {
    $defaultPassword = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$defaultPassword')");
}

// ---------- CATEGORIES TABLE ----------
$createCategoriesTable = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($createCategoriesTable);

// ---------- PRODUCTS TABLE ----------
$createProductsTable = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
)";
$conn->query($createProductsTable);

// ---------- ORDERS TABLE ----------
$createOrdersTable = "CREATE TABLE IF NOT EXISTS orders (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11) NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  payment_status ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending',
  order_status ENUM('Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Processing',
  shipping_address TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($createOrdersTable);


// ---------- SETTINGS TABLE ----------
$createSettingsTable = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) NOT NULL,
    site_tagline VARCHAR(255) NOT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    favicon VARCHAR(255) DEFAULT NULL,
    theme_color VARCHAR(7) DEFAULT '#007bff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createSettingsTable);

// Wishlist Table
$createWishlistTable = "CREATE TABLE IF NOT EXISTS wishlist (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createWishlistTable);

// Cart Table
$createCartTable = "CREATE TABLE IF NOT EXISTS cart (
     id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createCartTable);


// ---------- COUPONS TABLE ----------
$createCouponsTable = "CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_percent INT NOT NULL, -- যেমন 10 = 10%
    active TINYINT(1) DEFAULT 1
)";
$conn->query($createCouponsTable);


// ---------- DEMO ORDERS INSERT ----------

// Check if any orders exist
$checkOrders = $conn->query("SELECT COUNT(*) AS total FROM orders");
$orderCount = $checkOrders->fetch_assoc()['total'];

if ($orderCount == 0) {
    // Get any existing user (for demo)
    $user = $conn->query("SELECT id FROM users LIMIT 1")->fetch_assoc();
    if (!$user) {
        // If no user exists, create one
        $conn->query("INSERT INTO users (name, email, number, password) 
                      VALUES ('Demo User', 'demo@example.com', '01700000000', '" . password_hash('123456', PASSWORD_BCRYPT) . "')");
        $user_id = $conn->insert_id;
    } else {
        $user_id = $user['id'];
    }

    // Insert demo orders
    $conn->query("INSERT INTO orders (user_id, total_amount, payment_method, payment_status, order_status, shipping_address) VALUES
        ($user_id, 1200.00, 'bKash', 'Paid', 'Delivered', 'Dhaka, Bangladesh'),
        ($user_id, 850.50, 'Cash on Delivery', 'Pending', 'Processing', 'Chittagong, Bangladesh'),
        ($user_id, 2300.75, 'Nagad', 'Failed', 'Cancelled', 'Khulna, Bangladesh')
    ");
}




?>
