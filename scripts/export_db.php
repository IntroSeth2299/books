<?php
// Usage (CLI):
// php scripts/export_db.php --host=localhost --user=root --pass= --db=BookAuthorDB --out=docs/data.json

// Parse simple CLI args
$opts = [];
foreach ($argv as $arg) {
    if (strpos($arg, '--') === 0) {
        $pair = substr($arg, 2);
        $parts = explode('=', $pair, 2);
        $key = $parts[0];
        $val = $parts[1] ?? '';
        $opts[$key] = $val;
    }
}

$host = $opts['host'] ?? 'localhost';
$user = $opts['user'] ?? 'root';
$password = $opts['pass'] ?? '';
$database = $opts['db'] ?? 'BookAuthorDB';
$outFile = $opts['out'] ?? __DIR__ . '/../docs/data.json';

echo "Connecting to $host as $user...\n";
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    fwrite(STDERR, "Connection failed: " . mysqli_connect_error() . "\n");
    exit(1);
}

function fetch_all($conn, $sql) {
    $res = mysqli_query($conn, $sql);
    if (!$res) return [];
    $rows = [];
    while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
    return $rows;
}

$data = [];
$data['books'] = fetch_all($conn, 'SELECT * FROM books');
$data['authors'] = fetch_all($conn, 'SELECT * FROM authors');
$data['book_details'] = fetch_all($conn, 'SELECT * FROM book_details');

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
if ($json === false) {
    fwrite(STDERR, "Failed to encode JSON\n");
    exit(1);
}

if (file_put_contents($outFile, $json) === false) {
    fwrite(STDERR, "Failed to write to $outFile\n");
    exit(1);
}

echo "Exported to $outFile\n";
exit(0);
