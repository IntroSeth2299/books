<?php
include '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$result = mysqli_query($conn, "SELECT * FROM authors WHERE author_id = $id");
$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = mysqli_prepare($conn, "UPDATE authors SET name=?, email=?, address=? WHERE author_id=?");
    mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $address, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header('Location: list.php');
    exit;
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Author</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Author</h2>
        <a href="list.php" class="btn-back">Back</a>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>">
            <label>Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($row['address']); ?>">
            <button class="btn-edit" type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
