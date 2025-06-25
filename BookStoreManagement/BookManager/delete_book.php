<?php
session_start();
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $book_id = $_GET['book_id'] ?? 0;
        
        $stmt_delete_book = $conn->prepare("DELETE FROM books WHERE book_id = ?");
        $stmt_delete_book->bind_param('i', $book_id);
        
        if($stmt_delete_book->execute()){
            header("Location: books.php");
            exit();
        }else{
            echo "Error delete " . $stmt_delete_book->error();
        }
        $stmt_delete_book->close();
        $conn->close();
        ?>
    </body>
</html>