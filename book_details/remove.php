<?php
include '../config.php';

$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
$author_id = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;

if ($book_id && $author_id) {
    // Soft delete the assignment by composite key
    $stmt = mysqli_prepare($conn, "UPDATE book_details SET is_deleted=1 WHERE book_id=? AND author_id=?");
    mysqli_stmt_bind_param($stmt, 'ii', $book_id, $author_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: assign.php');
exit;
?>
