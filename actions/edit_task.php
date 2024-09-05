<?php
session_start();

include("../settings/connection.php");

if (isset($_POST['editTask'])) {
    $userID = $_SESSION['user_id'];
    $taskID = $_GET['task_id']; // Get the task ID from the query string
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
    
    // Get project_id from POST (in case it's not in the URL)
    $listID = isset($_GET['project_id']) ? $_GET['project_id'] : $_POST['project_id'];

    if (empty($taskName)) {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task name cannot be empty!"));
        exit();
    }

    $sql = "UPDATE tasks SET name = ?, description = ? WHERE id = ? AND project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $taskName, $description, $taskID, $listID);

    if ($stmt->execute()) {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task updated successfully"));
        exit();
    } else {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Error updating task, please try again."));
        exit();
    }
} else {
    $listID = isset($_GET['project_id']) ? $_GET['project_id'] : $_POST['project_id']; // Ensure project_id is captured
    header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Error editing task, please try again."));
    exit();
}
