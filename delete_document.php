<?php
include("config/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Instead of DELETE, mark as Deleted
    $sql = "UPDATE documents SET status = 'Deleted' WHERE doc_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php?msg=Document marked as deleted");
    exit;
}
?>
