<?php
include '../db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    
    $result = $conn->query("SELECT image FROM sliders WHERE id=$id");
    $row = $result->fetch_assoc();

    if ($row) {
        $imagePath = "../uploads/" . $row['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // ফাইল ডিলিট করবে
        }
        $conn->query("DELETE FROM sliders WHERE id=$id");
    }
}

header("Location: slider.php?deleted=1");
exit;
?>
