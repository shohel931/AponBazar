<?php 
$host = 'localhost';
$dbname = 'aponbazar';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


// sql to create users table if it doesn't exist
$createUsersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    number BIGINT(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($createUsersTable) === FALSE) {
    echo "Error creating users table: " . $conn->error;
}

// sql to create admins table if it doesn't exist
$createAdminsTable = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($createAdminsTable) === FALSE) {
    echo "Error creating admins table: " . $conn->error;
}
// Insert default admin if not exists
$checkAdmin = $conn->query("SELECT * FROM admins WHERE username='admin'");
if ($checkAdmin->num_rows == 0) {
    $defaultPassword = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->query("INSERT INTO admins (username, password) VALUES ('admin', '$defaultPassword')");
}


// sql to create products table if it doesn't exist
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
if ($conn->query($createProductsTable) === FALSE) {
    echo "Error creating products table: " . $conn->error;
}

// sql to create categories table if it doesn't exist
$createCategoriesTable = "CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($createCategoriesTable) === FALSE) {
    echo "Error creating categories table: " . $conn->error;
}
// sql to create orders table if it doesn't exist
$createOrdersTable = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if ($conn->query($createOrdersTable) === FALSE) {
    echo "Error creating orders table: " . $conn->error;
}


?>
