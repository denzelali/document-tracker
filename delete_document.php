<?php
include("config/db.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM documents WHERE doc_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
