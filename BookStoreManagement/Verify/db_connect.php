<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookstoredb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
