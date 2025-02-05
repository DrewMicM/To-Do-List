<?php
// Include database connection
require_once 'conn.php';

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Get form data and sanitize inputs
    $task = trim(htmlspecialchars($_POST['task']));
    $date = $_POST['date'];
    $priority = $_POST['priority'];
    var_dump($priority);
    
    // Validate inputs
    if(empty($task) || empty($date) || empty($priority)) {
        echo "<script>
                alert('All fields are required!');
                window.location.href='task.php';
              </script>";
        exit();
    }
    
    // Validate date format
    if(!strtotime($date)) {
        echo "<script>
                alert('Invalid date format!');
                window.location.href='task.php';
              </script>";
        exit();
    }
    
    // Validate priority
    $allowed_priorities = ['HIGH', 'MEDIUM', 'LOW'];
    if(!in_array($priority, $allowed_priorities)) {
        echo "<script>
                alert('Invalid priority level!');
                window.location.href='task.php';
              </script>";
        exit();
    }
    
    try {
        // Prepare SQL statement to prevent SQL injection
        $query = $conn->prepare("INSERT INTO task (task, date, priority, status) VALUES (?, ?, ?, 'Pending')");
        $query->bind_param("sss", $task, $date, $priority);        
        
        // Execute the query
        if($query->execute()) {
            echo "<script>
                    alert('Task added successfully!');
                    window.location.href='task.php';
                  </script>";
        } else {
            throw new Exception("Error executing query");
        }        
    } catch(Exception $e) {
        echo "<script>
                alert('Error adding task: " . $e->getMessage() . "');
                window.location.href='task.php';
              </script>";
    }
    
    // Close the statement
    $query->close();
} else {
    // If accessed directly without form submission, redirect to task
    // header("Location: task.php");
    exit();
}

// Close database connection
$conn->close();
?>