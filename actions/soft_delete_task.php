<?php
session_start();

include '../settings/connection.php'; // Ensure this file sets up $conn

// Check if user is logged in
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: ../view/login.php");
    exit();
}

// Get task ID from URL
$taskID = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;
if (!$taskID) {
    echo "Error: No task ID provided.";
    exit();
}

// Prepare the SQL to move the task to the archived_tasks table
$stmt = $conn->prepare("INSERT INTO archived_tasks (id, project_id, description, completed, name, deleted_at)
    SELECT id, project_id, description, completed, name, NOW()
    FROM tasks WHERE id = ?");
$stmt->bind_param("i", $taskID);
if (!$stmt->execute()) {
    echo "Error: Could not move task to archive.";
    exit();
}

// Prepare the SQL to delete the task from the tasks table
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
$stmt->bind_param("i", $taskID);
if (!$stmt->execute()) {
    echo "Error: Could not delete task from the tasks table.";
    exit();
}

// Redirect back to the task list or wherever you want after deletion
header("Location: ../view/mainpage.php");
exit();
?>