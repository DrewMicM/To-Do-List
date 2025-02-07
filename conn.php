<?php
    $conn = new mysqli("localhost", "root", "", "db_task");
    
    if(!$conn){
        die("Connection failed: " . mysql_error());
    }

    $sql = "SELECT * FROM task";
    $result = $conn->query($sql);
?>