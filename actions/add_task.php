<?php
session_start();

include("../settings/connection.php");

if (isset($_POST['addTask'])) {
    $userID = $_SESSION['user_id'];
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
    $listID = $_GET['project_id'];

    if (empty($taskName)) {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task must be given a name!"));
        exit();
    }
    $completed = false; 

    $sql = "INSERT INTO tasks (project_id, description, name, completed) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $listID, $description, $taskName, $completed);

    if ($stmt->execute()) {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task added successfully"));
        exit();
    } else {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Error adding task, please try again."));
        exit();
    }
} else {
    header("Location: ../view/tasks.php");
    exit();
}
