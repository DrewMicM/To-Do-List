<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        .checkbox-lg {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .strikethrough {
            text-decoration: line-through;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="task-container bg-light rounded">
            <h3 class="text-center">To-Do List</h3>
            <div class="task-form">
                <form method="POST" action="add.php" class="d-flex justify-content-center align-items-center gap-2">
                    <input type="text" 
                           class="form-control" 
                           name="task" 
                           placeholder="Enter new task"
                           required
                           style="max-width: 300px;">
                    
                    <input type="date" 
                           class="form-control" 
                           name="date" 
                           required
                           value="<?php echo date('Y-m-d'); ?>"
                           min="<?php echo date('Y-m-d'); ?>"
                           style="max-width: 150px;">

                    <select name="priority"
                            class="form-control"
                            style="max-width: 150px;"
                            required>
                            <option value="" disabled selected>Select Priority</option>
                            <option value="HIGH">High</option>
                            <option value="MEDIUM">Medium</option>
                            <option value="LOW">Low</option>
                    </select>
                    
                    <input type="submit" 
                           class="btn btn-primary" 
                           name="submit"
                           value="Add Task">
                </form>
            </div>
            <div class="task-table">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Task</th>
                            <th scope="col">Date</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once 'conn.php';

                            $query = $conn->prepare("SELECT * FROM task ORDER BY 
                                CASE priority
                                    WHEN 'HIGH' THEN 1
                                    WHEN 'MEDIUM' THEN 2
                                    WHEN 'LOW' THEN 3
                                END,
                                date ASC");
                            $query->execute();
                            $result = $query->get_result();

                            $count = 1;
                            while ($fetch = $result->fetch_assoc()) {
                                $is_done = ($fetch['status'] == 'Done');
                                $is_overdue = (!$is_done && strtotime($fetch['date']) < strtotime(date('Y-m-d')));
                                $priority_class = 'priority-' . strtolower($fetch['priority']);
                                ?>
                                <tr class="<?= $is_done ? 'table-success' : ($is_overdue ? 'table-danger' : $priority_class) ?>">
                                    <td><?= $count++; ?></td>
                                    <td class="<?= $is_done ? 'strikethrough' : '' ?>">
                                        <?= htmlspecialchars($fetch['task']); ?>
                                    </td>
                                    <td>
                                        <?= date('d M Y', strtotime($fetch['date'])); ?>
                                        <?php if ($is_overdue && !$is_done): ?>
                                            <span class="badge bg-danger">Overdue</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php
                                            echo match(strtoupper($fetch['priority'])) {
                                                'HIGH' => 'bg-danger',
                                                'MEDIUM' => 'bg-warning text-dark',
                                                'LOW' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        ?>">
                                            <?= $fetch['priority']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" 
                                                   class="checkbox-lg me-2" 
                                                   <?= $is_done ? 'checked' : '' ?>
                                                   onchange="updateTaskStatus(<?= $fetch['task_id']; ?>, this.checked)"
                                                   id="task_<?= $fetch['task_id']; ?>">
                                            <?php if ($is_done): ?>
                                                <span class="badge bg-success" hidden>Done</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark" hidden>Pending</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="edit.php?task_id=<?= $fetch['task_id']; ?>" 
                                           class="btn btn-warning btn-sm me-2">Edit</a>
                                        <a href="delete_query.php?task_id=<?= $fetch['task_id']; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to delete this task?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function updateTaskStatus(taskId, isChecked) {
        fetch('update_task.php?task_id=' + taskId + '&status=' + (isChecked ? 'Done' : 'Pending'), {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update task status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating task status');
        });
    }
    </script>
</body>
</html>
