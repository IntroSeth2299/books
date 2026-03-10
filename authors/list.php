<?php
include '../config.php';
$result=mysqli_query($conn,"SELECT * FROM authors WHERE IFNULL(is_delete,0)=0");
?>

<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Authors</h2>
<a href="../" class="button btn-back">Back</a>
<a href="create.php" class="btn-add">Add Author</a>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Address</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['author_id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['address'] ?></td>
<td>
<a href="edit.php?id=<?= $row['author_id'] ?>" class="btn-edit">Edit</a>
<a href="delete.php?id=<?= $row['author_id'] ?>" class="btn-delete">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
