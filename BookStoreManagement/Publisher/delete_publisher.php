<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

if (isset($_GET['id'])) {
    $publisher_id = $_GET['id'];

    $check_sql = "SELECT COUNT(*) as count FROM books WHERE publisher_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $publisher_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $row = $result->fetch_assoc();
    $check_stmt->close();

    if ($row['count'] > 0) {
        header("Location: publishers.php?error=delete_failed");
        exit();
    }

    $delete_sql = "DELETE FROM publishers WHERE publisher_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $publisher_id);

    if ($delete_stmt->execute()) {
        header("Location: publishers.php?success=delete");
    } else {
        header("Location: publishers.php?error=delete_failed");
    }
    $delete_stmt->close();
} else {
    header("Location: publishers.php?error=invalid_id");
}
exit();
