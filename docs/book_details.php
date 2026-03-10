<?php
include __DIR__ . '/../config.php';
function h($s){ return htmlspecialchars($s); }
$error = '';

// create assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['action']) && $_POST['action'] === 'assign') {
        $book_id = intval($_POST['book_id']);
        $author_id = intval($_POST['author_id']);
        if ($book_id <= 0 || $author_id <= 0) { $error = 'Select valid book and author'; }
        else {
            $sql = "INSERT INTO book_details (book_id, author_id) VALUES ({$book_id}, {$author_id})";
            if (!$conn->query($sql)) { $error = 'Assign failed: ' . $conn->error; }
            else { header('Location: book_details.php'); exit; }
        }
    }
    if (!empty($_POST['action']) && $_POST['action'] === 'remove') {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $sql = "DELETE FROM book_details WHERE id = {$id}";
            if (!$conn->query($sql)) { $error = 'Remove failed: ' . $conn->error; }
            else { header('Location: book_details.php'); exit; }
        }
    }
}

// fetch lists
$books = [];
$res = $conn->query('SELECT id, title FROM books ORDER BY title');
if ($res) { while ($r = $res->fetch_assoc()) { $books[] = $r; } $res->free(); }

$authors = [];
$res = $conn->query('SELECT id, name FROM authors ORDER BY name');
if ($res) { while ($r = $res->fetch_assoc()) { $authors[] = $r; } $res->free(); }

$assignments = [];
$sql = 'SELECT bd.id, b.title AS book_title, a.name AS author_name FROM book_details bd JOIN books b ON bd.book_id=b.id JOIN authors a ON bd.author_id=a.id ORDER BY bd.id DESC';
$res = $conn->query($sql);
if ($res) { while ($r = $res->fetch_assoc()) { $assignments[] = $r; } $res->free(); }

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Book Assignments - Docs</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <div class="container">
    <a href="index.php">← Back</a>
    <h2>Book Assignments</h2>
    <?php if ($error): ?><div class="error"><?php echo h($error); ?></div><?php endif; ?>

    <form method="post" class="form-inline">
      <select name="book_id">
        <option value="">Select book</option>
        <?php foreach ($books as $b): ?>
          <option value="<?php echo h($b['id']); ?>"><?php echo h($b['title']); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="author_id">
        <option value="">Select author</option>
        <?php foreach ($authors as $a): ?>
          <option value="<?php echo h($a['id']); ?>"><?php echo h($a['name']); ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" name="action" value="assign">Assign</button>
    </form>

    <table>
      <thead><tr><th>ID</th><th>Book</th><th>Author</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($assignments as $a): ?>
          <tr>
            <td><?php echo h($a['id']); ?></td>
            <td><?php echo h($a['book_title']); ?></td>
            <td><?php echo h($a['author_name']); ?></td>
            <td>
              <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?php echo h($a['id']); ?>">
                <button type="submit" name="action" value="remove" onclick="return confirm('Remove this assignment?')">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
