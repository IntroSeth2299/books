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

<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Add Author</h2>
<a href="../" class="button btn-back">Back</a>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="address" placeholder="Address">
    <input type="submit" name="submit" value="Save">
</form>
</div>
