<?php
session_start();

include("../settings/connection.php");

if (isset($_POST['addTask'])) {
    $userID = $_SESSION['user_id'];
    $taskName = trim($_POST['taskName']);
    $description = trim($_POST['description']);
<<<<<<< HEAD
    $listID = 2; //(int)$_GET['id'];

    if (empty($taskName)) {
        header("Location: ../view/tasks.php?msg=Task must be given a name!");
=======
    $listID = $_GET['project_id'];

    if (empty($taskName)) {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task must be given a name!"));
>>>>>>> Kwame
        exit();
    }
    $completed = false; 

<<<<<<< HEAD
    $sql = "INSERT INTO tasks (list_id, description, name, completed) VALUES (?, ?, ?, ?)";
=======
    $sql = "INSERT INTO tasks (project_id, description, name, completed) VALUES (?, ?, ?, ?)";
>>>>>>> Kwame
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $listID, $description, $taskName, $completed);

    if ($stmt->execute()) {
<<<<<<< HEAD
        header("Location: ../view/tasks.php?msg=Task added successfully");
        exit();
    } else {
        //error_log("Task insertion error: " . $conn->error);
        header("Location: ../view/tasks.php?msg=Error adding task, please try again.");
        exit();
    }
} else {
    header("Location: ../view/task.php");
=======
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Task added successfully"));
        exit();
    } else {
        header("Location: ../view/tasks.php?project_id=" . urlencode($listID) . "&msg=" . urlencode("Error adding task, please try again."));
        exit();
    }
} else {
    header("Location: ../view/tasks.php?project_id=" . urlencode($listID)  . "&msg=" . urlencode("Error adding task, please try again."));
>>>>>>> Kwame
    exit();
}
