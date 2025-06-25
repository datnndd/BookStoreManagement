<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: /BookStoreManagement/Verify/login_admin.php");
    exit();
}

?>
