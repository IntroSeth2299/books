<?php
include '../config.php';

if(isset($_POST['assign'])){
	$book = (int)$_POST['book_id'];
	$author = (int)$_POST['author_id'];

	// Prevent duplicate assignment (composite primary key)
	$stmt = mysqli_prepare($conn, "SELECT 1 FROM book_details WHERE book_id=? AND author_id=? AND IFNULL(is_deleted,0)=0");
	mysqli_stmt_bind_param($stmt, 'ii', $book, $author);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_store_result($stmt);
	$exists = mysqli_stmt_num_rows($stmt) > 0;
	mysqli_stmt_close($stmt);

	if (!$exists) {
		$ins = mysqli_prepare($conn, "INSERT INTO book_details (book_id, author_id) VALUES (?, ?)");
		mysqli_stmt_bind_param($ins, 'ii', $book, $author);
		mysqli_stmt_execute($ins);
		mysqli_stmt_close($ins);
	}

	header('Location: assign.php');
	exit;
}

$books=mysqli_query($conn,"SELECT * FROM books WHERE IFNULL(is_delete,0)=0");
$authors=mysqli_query($conn,"SELECT * FROM authors WHERE IFNULL(is_delete,0)=0");
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Assign Author to Book</title>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<div class="container">
		<h2>Assign Author to Book</h2>
		<a href="../" class="btn-back">Back</a>
		<form method="POST">
			<label>Select Book</label>
			<select name="book_id" required>
				<option value="">Select Book</option>
				<?php while($b=mysqli_fetch_assoc($books)){ ?>
					<option value="<?= $b['book_id'] ?>"><?= htmlspecialchars($b['title']) ?></option>
				<?php } ?>
			</select>

			<label>Select Author</label>
			<select name="author_id" required>
				<option value="">Select Author</option>
				<?php while($a=mysqli_fetch_assoc($authors)){ ?>
					<option value="<?= $a['author_id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
				<?php } ?>
			</select>

			<button class="btn-assign" type="submit" name="assign">Assign</button>
		</form>
	</div>

	<div class="container">
		<h3>Current Assignments</h3>
		<?php
		$assigns = mysqli_query($conn, "SELECT bd.book_id, bd.author_id, b.title, a.name
				FROM book_details bd
				JOIN books b ON bd.book_id=b.book_id
				JOIN authors a ON bd.author_id=a.author_id
				WHERE IFNULL(bd.is_deleted,0)=0");
		if (mysqli_num_rows($assigns) == 0) {
				echo '<p>No assignments yet.</p>';
		} else {
				echo '<table>';
				echo '<thead><tr><th>Book</th><th>Author</th><th>Action</th></tr></thead><tbody>';
				while ($r = mysqli_fetch_assoc($assigns)) {
						echo '<tr>';
						echo '<td>'.htmlspecialchars($r['title']).'</td>';
						echo '<td>'.htmlspecialchars($r['name']).'</td>';
						echo '<td>';
						echo '<a class="btn-delete" href="remove.php?book_id='.(int)$r['book_id'].'&author_id='.(int)$r['author_id'].'">Remove</a>';
						echo '</td>';
						echo '</tr>';
				}
				echo '</tbody></table>';
		}
		?>
	</div>
</body>
</html>
