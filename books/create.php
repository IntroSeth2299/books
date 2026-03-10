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

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Add Book</title>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<div class="container">
		<h2>Add Book</h2>
		<a href="list.php" class="btn-back">Back</a>
		<form method="POST">
			<label>Title</label>
			<input type="text" name="title" placeholder="Title" required>
			<label>Pages</label>
			<input type="number" name="page" placeholder="Pages">
			<label>Publish Year</label>
			<input type="number" name="publish_year" placeholder="Publish Year">
			<label>Category</label>
			<input type="text" name="category" placeholder="Category">
			<button class="btn-add" type="submit" name="submit">Save</button>
		</form>
	</div>
</body>
</html>
