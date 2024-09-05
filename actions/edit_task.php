<?php
session_start();
include("../settings/connection.php");

if (isset($_GET['taskId'])) {
    $taskId = (int)$_GET['taskId'];

    $stmt = $conn->prepare("SELECT id, name, description FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found.";
        exit();
    }
}

if (isset($_POST['updateTask'])) {
    $taskId = (int)$_POST['taskId'];
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);

    if (empty($taskName)) {
        echo "Task name cannot be empty.";
        exit();
    }

    $stmt = $conn->prepare("UPDATE tasks SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $taskName, $description, $taskId);

    if ($stmt->execute()) {
        header("Location: ../view/mainpage.php?msg=Task updated successfully");
        exit();
    } else {
        echo "Error updating task: " . $conn->error;
    }
}
?>
