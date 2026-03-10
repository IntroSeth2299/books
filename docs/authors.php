<?php
include __DIR__ . '/../config.php';
function h($s){ return htmlspecialchars($s); }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action']) && $_POST['action'] === 'create') {
        $name = $conn->real_escape_string($_POST['name'] ?? '');
        if ($name === '') { $error = 'Name required'; }
        else {
            $sql = "INSERT INTO authors (name) VALUES ('{$name}')";
            if (!$conn->query($sql)) { $error = 'Insert failed: ' . $conn->error; }
            else { header('Location: authors.php'); exit; }
        }
    }
    if (!empty($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            // Also remove book_details entries referencing this author
            $conn->query("DELETE FROM book_details WHERE author_id={$id}");
            $sql = "DELETE FROM authors WHERE id = {$id}";
            if (!$conn->query($sql)) { $error = 'Delete failed: ' . $conn->error; }
            else { header('Location: authors.php'); exit; }
        }
    }
}

$authors = [];
$res = $conn->query('SELECT id, name FROM authors ORDER BY id DESC');
if ($res) {
    while ($row = $res->fetch_assoc()) { $authors[] = $row; }
    $res->free();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Authors - Docs</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <a href="index.php">← Back</a>
    <h2>Authors</h2>
    <?php if ($error): ?><div class="error"><?php echo h($error); ?></div><?php endif; ?>

    <form method="post" class="form-inline">
      <input name="name" placeholder="Author name">
      <button type="submit" name="action" value="create">Add</button>
    </form>

    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($authors as $a): ?>
          <tr>
            <td><?php echo h($a['id']); ?></td>
            <td><?php echo h($a['name']); ?></td>
            <td>
              <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?php echo h($a['id']); ?>">
                <button type="submit" name="action" value="delete" onclick="return confirm('Delete this author?')">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
