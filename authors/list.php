<?php
include '../config.php';
$result=mysqli_query($conn,"SELECT * FROM authors WHERE IFNULL(is_delete,0)=0");
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Authors</title>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<div class="container">
		<h2>Authors</h2>
		<a href="../" class="btn-back">Back</a>
		<a href="create.php" class="btn-add">Add Author</a>

		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php while($row=mysqli_fetch_assoc($result)){ ?>
				<tr>
					<td><?= $row['author_id'] ?></td>
					<td><?= htmlspecialchars($row['name']) ?></td>
					<td><?= htmlspecialchars($row['email']) ?></td>
					<td><?= htmlspecialchars($row['address']) ?></td>
					<td>
						<a href="edit.php?id=<?= $row['author_id'] ?>" class="btn-edit">Edit</a>
						<a href="delete.php?id=<?= $row['author_id'] ?>" class="btn-delete">Delete</a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</body>
</html>
