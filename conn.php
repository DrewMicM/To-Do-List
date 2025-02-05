<?php
    $conn = new mysqli("localhost", "root", "", "db_task");

    if(!$conn){
        die("Error: Cannot connect to the database");
    }
    $sql = "SELECT * FROM task";
    $result = $conn->query($sql)
?>