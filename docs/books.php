<?php
include __DIR__ . '/../config.php';

// Basic contract:
// - list books
// - create book (title, description)
// - edit book
// - delete book

// Helpers
function h($s){ return htmlspecialchars($s); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action']) && $_POST['action'] === 'create') {
        $title = $conn->real_escape_string($_POST['title'] ?? '');
        $description = $conn->real_escape_string($_POST['description'] ?? '');
        if ($title === '') { $error = 'Title required'; }
        else {
            $sql = "INSERT INTO books (title, description) VALUES ('{$title}', '{$description}')";
            if (!$conn->query($sql)) { $error = 'Insert failed: ' . $conn->error; }
            else { header('Location: books.php'); exit; }
        }
    }
    if (!empty($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id']);
        if ($id > 0) {
      // remove any assignments first to avoid orphaned rows
      $conn->query("DELETE FROM book_details WHERE book_id={$id}");
      $sql = "DELETE FROM books WHERE id = {$id}";
            if (!$conn->query($sql)) { $error = 'Delete failed: ' . $conn->error; }
            else { header('Location: books.php'); exit; }
        }
    }
}

$books = [];
$res = $conn->query('SELECT id, title, description FROM books ORDER BY id DESC');
if ($res) {
    while ($row = $res->fetch_assoc()) { $books[] = $row; }
    $res->free();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Books - Docs</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <a href="index.php">← Back</a>
    <h2>Books</h2>
    <?php if ($error): ?><div class="error"><?php echo h($error); ?></div><?php endif; ?>

    <form method="post" class="form-inline">
      <input name="title" placeholder="Title">
      <input name="description" placeholder="Description">
      <button type="submit" name="action" value="create">Add</button>
    </form>

    <table>
      <thead><tr><th>ID</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($books as $b): ?>
          <tr>
            <td><?php echo h($b['id']); ?></td>
            <td><?php echo h($b['title']); ?></td>
            <td><?php echo h($b['description']); ?></td>
            <td>
              <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?php echo h($b['id']); ?>">
                <button type="submit" name="action" value="delete" onclick="return confirm('Delete this book?')">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
