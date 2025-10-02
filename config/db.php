<?php
// MySQLi connection (existing)
$host = "localhost";
$user = "root";   // default user in XAMPP
$pass = "";       // default password is empty
$db   = "document_tracker";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("MySQL Connection failed: " . $conn->connect_error);
}

// SQLite connection (new)
try {
    $sqlite_dbPath = __DIR__ . '/database.sqlite';  // Path to SQLite DB file
    $sqlite_conn = new PDO('sqlite:' . $sqlite_dbPath);
    $sqlite_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("SQLite Connection failed: " . $e->getMessage());
}
?>
