<?php
include '../config.php';

if(isset($_POST['submit'])){
	$title = $_POST['title'] ?? '';
	$page = (int)($_POST['page'] ?? 0);
	$year = (int)($_POST['publish_year'] ?? 0);
	$category = $_POST['category'] ?? '';

	$stmt = mysqli_prepare($conn, "INSERT INTO books (title, page, publish_year, category) VALUES (?, ?, ?, ?)");
	mysqli_stmt_bind_param($stmt, 'siis', $title, $page, $year, $category);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

	header("Location:list.php");
	exit;
}
?>

<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Add Book</h2>
<a href="../" class="button btn-back">Back</a>
<form method="POST">
<input type="text" name="title" placeholder="Title" required>
<input type="number" name="page" placeholder="Pages">
<input type="number" name="publish_year" placeholder="Publish Year">
<input type="text" name="category" placeholder="Category">
<input type="submit" name="submit" value="Save">
</form>
</div>
