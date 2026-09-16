<?php
// Restaura los datos iniciales desde database/dumps/db_maletin.sql
// Solo importa si la tabla vehicles está vacía (no sobreescribe datos).
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (function_exists('mysqli_report')) { mysqli_report(MYSQLI_REPORT_OFF); }

$host = getenv('DB_HOST');
if ($host === false) { $host = '127.0.0.1'; }
$port = getenv('DB_PORT');
if ($port === false) { $port = '3306'; }
$user = getenv('DB_USERNAME');
if ($user === false) { $user = 'root'; }
$pass = getenv('DB_PASSWORD');
if ($pass === false) { $pass = ''; }
$db   = getenv('DB_DATABASE');
if ($db === false) { $db = ''; }

if (!$db) {
    echo "RESTORE_SKIP no-db\n";
    exit(0);
}

$mysqli = @new mysqli();
if (getenv('DB_SSL')) {
    @$mysqli->ssl_set(null, null, '/etc/ssl/certs/ca-certificates.crt', null, null);
    @$mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
}
@$mysqli->real_connect($host, $user, $pass, $db, (int)$port);
if ($mysqli->connect_errno) {
    echo "RESTORE_CONN_ERR: " . $mysqli->connect_error . "\n";
    exit(0);
}
$mysqli->set_charset('utf8mb4');

$count = 0;
$res = $mysqli->query("SELECT COUNT(*) AS c FROM vehicles");
if ($res) {
    $row = $res->fetch_assoc();
    $count = (int)$row['c'];
    $res->free();
}

if ($count > 0) {
    echo "RESTORE_SKIP has-data\n";
    $mysqli->close();
    exit(0);
}

$candidates = [
    'database/dumps/db_maletin.sql',
    __DIR__ . '/dumps/db_maletin.sql',
    'dumps/db_maletin.sql',
];
$file = null;
foreach ($candidates as $candidate) {
    if (is_file($candidate)) { $file = $candidate; break; }
}
if (!$file) {
    echo "RESTORE_NO_SQL\n";
    $mysqli->close();
    exit(0);
}

$sql = file_get_contents($file);
$ok = @$mysqli->multi_query($sql);
if (!$ok) {
    echo "RESTORE_ERR: " . $mysqli->error . "\n";
    $mysqli->close();
    exit(0);
}
while ($mysqli->more_results() && $mysqli->next_result()) { ; }

$tables = [];
$r = $mysqli->query("SHOW TABLES");
if ($r) { while (($row = $r->fetch_row())) { $tables[] = $row[0]; } $r->free(); }
echo "RESTORE_OK tables=" . implode(',', $tables) . "\n";
$mysqli->close();