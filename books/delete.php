<?php
include '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
	$stmt = mysqli_prepare($conn, "UPDATE books SET is_delete=1 WHERE book_id=?");
	mysqli_stmt_bind_param($stmt, 'i', $id);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);
}

header("Location:list.php");
exit;
?>
