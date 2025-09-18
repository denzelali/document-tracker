<?php
$host = "localhost";
$user = "root";   // default user in XAMPP
$pass = "";       // default password is empty
$db   = "document_tracker";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
