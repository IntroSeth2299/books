<?php
include '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM books WHERE book_id = $id");
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $title = $_POST['title'] ?? '';
    $page = (int)($_POST['page'] ?? 0);
    $year = (int)($_POST['publish_year'] ?? 0);
    $category = $_POST['category'] ?? '';

    $stmt = mysqli_prepare($conn, "UPDATE books SET title=?, page=?, publish_year=?, category=? WHERE book_id=?");
    mysqli_stmt_bind_param($stmt, 'siisi', $title, $page, $year, $category, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h2>Edit Book</h2>
    <a href="../" class="button btn-back">Back</a>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($row['title']); ?>" required>
        <input type="number" name="page" value="<?= htmlspecialchars($row['page']); ?>">
        <input type="number" name="publish_year" value="<?= htmlspecialchars($row['publish_year']); ?>">
        <input type="text" name="category" value="<?= htmlspecialchars($row['category']); ?>">
        <input type="submit" name="update" value="Update">
    </form>
</div>
</body>
</html>
