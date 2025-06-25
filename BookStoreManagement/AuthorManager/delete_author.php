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
        $author_id = $_GET['author_id'] ?? 0;
        
        $stmt_delete_author = $conn->prepare("DELETE FROM author WHERE author_id = ?");
        $stmt_delete_author->bind_param('i', $author_id);
        
        if($stmt_delete_author->execute()){
            header("Location: authors.php");
            exit();
        }else{
            echo "Error delete " . $stmt_delete_author->error();
        }
        $stmt_delete_author->close();
        $conn->close();
        ?>
    </body>
</html>