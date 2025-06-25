<?php
include '../Verify/auth.php';
include '../Verify/db_connect.php';

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

$stmt_delete_category = $conn->prepare("DELETE FROM categories WHERE category_id = ?");
if ($stmt_delete_category === false) {
    echo "Lỗi chuẩn bị truy vấn: " . $conn->error;
} else {
    $stmt_delete_category->bind_param('i', $category_id);
    if ($stmt_delete_category->execute()) {
        header("Location: categories.php");
        exit();
    } else {
        echo "Lỗi khi xóa: " . $stmt_delete_category->error;
    }
}
$stmt_delete_category->close();
$conn->close();
?>
