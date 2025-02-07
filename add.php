<?php
require_once 'conn.php';

if(isset($_POST['submit'])) {
    // Get and sanitize input
    $task = trim(htmlspecialchars($_POST['task']));
    $date = $_POST['date'];
    $priority = $_POST['priority'];
    
    // Simple validation
    if(empty($task) || empty($date) || empty($priority)) {
        echo "<script>alert('Please fill all fields!'); window.location.href='task.php';</script>";
        exit();
    }
    
    // Insert task
    $query = $conn->prepare("INSERT INTO task (task, date, priority, status) VALUES (?, ?, ?, 'Pending')");
    $query->bind_param("sss", $task, $date, $priority);
    
    if($query->execute()) {
        echo "<script>alert('Task added successfully!'); window.location.href='task.php';</script>";
    } else {
        echo "<script>alert('Error adding task!'); window.location.href='task.php';</script>";
    }
    
    $query->close();
    $conn->close();
} else {
    header("Location: task.php");
    exit();
}
?>