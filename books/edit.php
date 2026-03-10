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

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Book</h2>
        <a href="list.php" class="btn-back">Back</a>
        <form method="POST">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($row['title']); ?>" required>
            <label>Pages</label>
            <input type="number" name="page" value="<?= htmlspecialchars($row['page']); ?>">
            <label>Publish Year</label>
            <input type="number" name="publish_year" value="<?= htmlspecialchars($row['publish_year']); ?>">
            <label>Category</label>
            <input type="text" name="category" value="<?= htmlspecialchars($row['category']); ?>">
            <button class="btn-edit" type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
