<?php
include '../config.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt = mysqli_prepare($conn, "INSERT INTO authors (name, email, address) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $address);
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
    <title>Add Author</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Add Author</h2>
        <a href="list.php" class="btn-back">Back</a>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" placeholder="Name" required>
            <label>Email</label>
            <input type="email" name="email" placeholder="Email">
            <label>Address</label>
            <input type="text" name="address" placeholder="Address">
            <button class="btn-add" type="submit" name="submit">Save</button>
        </form>
    </div>
</body>
</html>
