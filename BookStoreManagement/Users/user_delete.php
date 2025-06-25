<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';


$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: users.php");
exit();
