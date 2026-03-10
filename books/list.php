<?php
include '../config.php';
$result = mysqli_query($conn, "
SELECT b.*, 
GROUP_CONCAT(a.name SEPARATOR ', ') as authors
FROM books b
LEFT JOIN book_details bd ON b.book_id = bd.book_id AND bd.is_deleted=0
LEFT JOIN authors a ON bd.author_id = a.author_id
WHERE b.is_delete=0
GROUP BY b.book_id
");
?>

<link rel="stylesheet" href="../style.css">
<div class="container">
<h2>Books</h2>
<a href="../" class="button btn-back">Back</a>
<a href="create.php" class="btn-add">Add Book</a>

<table>
<tr>
<th>ID</th>
<th>Title</th>
<th>Year</th>
<th>Category</th>
<th>Authors</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['book_id'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['publish_year'] ?></td>
<td><?= $row['category'] ?></td>
<td><?= $row['authors'] ?></td>
<td>
<a href="edit.php?id=<?= $row['book_id'] ?>" class="btn-edit">Edit</a>
<a href="delete.php?id=<?= $row['book_id'] ?>" class="btn-delete">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
