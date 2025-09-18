<?php
include("config/db.php");

header('Content-Type: application/json');

if ($_POST['doc_id'] && $_POST['status']) {
    $doc_id = intval($_POST['doc_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Validate status value
    $allowed_statuses = ['Approved', 'In Review', 'Rejected', 'Pending'];
    if (!in_array($status, $allowed_statuses)) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value']);
        exit;
    }
    
    $sql = "UPDATE documents SET status = ? WHERE doc_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $doc_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
}

$conn->close();
?>