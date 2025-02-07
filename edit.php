<?php
require_once 'conn.php';

// Redirect if no task_id
if (!isset($_GET['task_id'])) {
    header('Location: task.php');
    exit();
}

$task_id = $_GET['task_id'];

// Get task data
$query = $conn->query("SELECT * FROM task WHERE task_id = $task_id");
$task = $query->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_task = $_POST['task'];
    $new_date = $_POST['date'];
    $new_priority = $_POST['priority'];

    $update = $conn->query("UPDATE task SET 
        task = '$new_task', 
        date = '$new_date', 
        priority = '$new_priority' 
        WHERE task_id = $task_id");
    
    if ($update) {
        header('Location: task.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Edit Task</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label>Task</label>
                        <input type="text" 
                               class="form-control" 
                               name="task" 
                               value="<?= $task['task'] ?>" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Date</label>
                        <input type="date" 
                               class="form-control" 
                               name="date" 
                               value="<?= $task['date'] ?>" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Priority</label>
                        <select class="form-control" name="priority" required>
                            <option value="HIGH" <?= $task['priority'] == 'High' ? 'selected' : '' ?>>High</option>
                            <option value="MEDIUM" <?= $task['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="LOW" <?= $task['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="task.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>