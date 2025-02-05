<?php
require_once 'conn.php';

// Check if task_id is provided in URL
if (!isset($_GET['task_id'])) {
    header('Location: task.php');
    exit();
}

$task_id = $_GET['task_id'];

// Fetch the existing task data
$query = $conn->prepare("SELECT * FROM task WHERE task_id = ?");
$query->bind_param("i", $task_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    header('Location: task.php');
    exit();
}

$task = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_task = $_POST['task'];
    $new_date = $_POST['date'];
    $new_priority = $_POST['priority'];

    // Update the task
    $update_query = $conn->prepare("UPDATE task SET task = ?, date = ?, priority = ? WHERE task_id = ?");
    $update_query->bind_param("sssi", $new_task, $new_date, $new_priority, $task_id);
    
    if ($update_query->execute()) {
        header('Location: task.php');
        exit();
    } else {
        $error_message = "Error updating task: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Edit Task</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="task" class="form-label">Task</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="task" 
                                       name="task" 
                                       value="<?php echo htmlspecialchars($task['task']); ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="date" 
                                       name="date" 
                                       value="<?php echo $task['date']; ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="HIGH" <?php echo $task['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                                    <option value="MEDIUM" <?php echo $task['priority'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="LOW" <?php echo $task['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Task</button>
                                <a href="task.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>