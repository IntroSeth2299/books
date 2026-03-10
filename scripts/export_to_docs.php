<?php
// Export DB tables to docs/data.json for static GitHub Pages preview
// Usage: php scripts/export_to_docs.php

$base = dirname(__DIR__);
require_once $base . '/config.php'; // expects $conn (mysqli)

header_remove();
// fetch books
$out = ['books'=>[], 'authors'=>[], 'book_details'=>[]];

$res = $conn->query('SELECT id, title, description FROM books ORDER BY id ASC');
if ($res) {
    while ($r = $res->fetch_assoc()) { $out['books'][] = $r; }
    $res->free();
}

$res = $conn->query('SELECT id, name FROM authors ORDER BY id ASC');
if ($res) {
    while ($r = $res->fetch_assoc()) { $out['authors'][] = $r; }
    $res->free();
}

$sql = 'SELECT bd.id, bd.book_id, bd.author_id, b.title AS book_title, a.name AS author_name '
    . 'FROM book_details bd '
    . 'LEFT JOIN books b ON bd.book_id=b.id '
    . 'LEFT JOIN authors a ON bd.author_id=a.id '
    . 'ORDER BY bd.id ASC';
$res = $conn->query($sql);
if ($res) {
    while ($r = $res->fetch_assoc()) { $out['book_details'][] = $r; }
    $res->free();
}

$json = json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    fwrite(STDERR, "Failed to encode JSON\n");
    exit(2);
}

$file = $base . '/docs/data.json';
if (file_put_contents($file, $json) === false) {
    fwrite(STDERR, "Failed to write $file\n");
    exit(3);
}

echo "Wrote $file (books=" . count($out['books']) . ", authors=" . count($out['authors']) . ", assignments=" . count($out['book_details']) . ")\n";
