<?php
require_once 'conn.php';

if (isset($_GET['task_id']) && isset($_GET['status'])) {
    $task_id = $_GET['task_id'];
    $status = $_GET['status'];
    
    $query = $conn->prepare("UPDATE task SET status = ? WHERE task_id = ?");
    $query->bind_param("si", $status, $task_id);
    
    $response = ['success' => $query->execute()];
    echo json_encode($response);
} else {
    echo json_encode(['success' => false]);
}
?>