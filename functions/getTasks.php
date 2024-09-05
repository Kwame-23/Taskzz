<?php
include '../settings/connection.php';

// Function to fetch all tasks for a specific project and user
function getTasks($user_id, $project_id) {
    global $conn;

    // Fetch tasks associated with the project
    $stmt = $conn->prepare("SELECT id, name, description, completed FROM tasks WHERE project_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $project_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    $stmt->close();
    return $tasks;
}

// Function to fetch a specific task by ID
function getTaskById($taskID) {
    global $conn;

    // Fetch a task by its ID
    $stmt = $conn->prepare("SELECT id, name, description, completed FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return the task if found
    return $result->fetch_assoc();
}
?>