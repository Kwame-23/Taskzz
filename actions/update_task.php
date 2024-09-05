<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php'; // Ensure this file sets up $conn

// Check if user is logged in
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: login.php");
    exit();
}

// Check if form data is submitted
if (isset($_POST['update_task'])) {
    $taskID = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;
    $taskName = isset($_POST['task_name']) ? $_POST['task_name'] : '';
    $taskDescription = isset($_POST['task_description']) ? $_POST['task_description'] : '';
    $taskCompleted = isset($_POST['task_completed']) ? 1 : 0; // Convert checkbox to 1 or 0

    // Validate input
    if ($taskID && !empty($taskName) && !empty($taskDescription)) {
        // Prepare and execute update query
        $stmt = $conn->prepare("UPDATE tasks SET name = ?, description = ?, completed = ? WHERE id = ?");
        $stmt->bind_param("ssii", $taskName, $taskDescription, $taskCompleted, $taskID);

        if ($stmt->execute()) {
            // Redirect to main page
            header("Location: ../view/mainpage.php");
            exit();
        } else {
            echo "Error: Unable to update task.";
        }

        $stmt->close();
    } else {
        echo "Error: Invalid input.";
    }
} else {
    echo "Error: Form not submitted.";
}
?>