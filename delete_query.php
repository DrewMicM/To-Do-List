<?php
    require_once 'conn.php';
       
    if($_GET['task_id']){
        $task_id = $_GET['task_id'];
        $sql = "SELECT * FROM tasks WHERE id = '$task_id'";
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
               
        $conn->query("DELETE FROM `task` WHERE `task_id` = $task_id") or die(mysqli_errno($conn));
        header("location: task.php");
    } 
?>