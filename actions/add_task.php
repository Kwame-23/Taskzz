<?php
session_start();

include("../settings/connection.php");

if (isset($_POST['addTask'])) {
    $userID = $_SESSION['user_id'];
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
    $listID = 2; //(int)$_GET['id'];

    if (empty($taskName)) {
        header("Location: ../view/tasks.php?msg=Task must be given a name!");
        exit();
    }
    $completed = false; 

    $sql = "INSERT INTO tasks (list_id, description, name, completed) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $listID, $description, $taskName, $completed);

    if ($stmt->execute()) {
        header("Location: ../view/tasks.php?msg=Task added successfully");
        exit();
    } else {
        //error_log("Task insertion error: " . $conn->error);
        header("Location: ../view/tasks.php?msg=Error adding task, please try again.");
        exit();
    }
} else {
    header("Location: ../view/task.php");
    exit();
}
