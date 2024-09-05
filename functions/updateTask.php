<?php
// This file would typically contain functions for updating tasks,
// but if you are handling the update directly in `update_task.php`, 
// you might not need it separately. If needed, you can leave it empty
// or add relevant functions based on your application.

function updateTask($taskID, $taskName, $taskDescription, $taskCompleted) {
    global $conn;

    // Update the task in the database
    $stmt = $conn->prepare("UPDATE tasks SET name = ?, description = ?, completed = ? WHERE id = ?");
    $stmt->bind_param("ssii", $taskName, $taskDescription, $taskCompleted, $taskID);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }

    $stmt->close();
}
?>