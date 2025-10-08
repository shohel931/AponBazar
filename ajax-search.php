<?php
include 'db.php';

if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
    if ($q != '') {
        $stmt = $conn->prepare("
            SELECT p.name AS product_name, c.name AS category_name 
            FROM products p 
            JOIN categories c ON p.category_id = c.id
            WHERE p.name LIKE CONCAT('%', ?, '%') 
            OR c.name LIKE CONCAT('%', ?, '%')
            LIMIT 10
        ");
        $stmt->bind_param("ss", $q, $q);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<ul class="suggestions">';
            while ($row = $result->fetch_assoc()) {
                $keyword = htmlspecialchars($row['product_name']);
                $cat = htmlspecialchars($row['category_name']);
                echo "<li><a href='shop.php?search={$keyword}'><strong>{$keyword}</strong> <span>in {$cat}</span></a></li>";
            }
            echo '</ul>';
        } else {
            echo '<p class="no-result">No result found</p>';
        }
    }
}
?>
