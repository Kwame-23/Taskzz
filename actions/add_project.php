<?php
session_start();

include("../settings/connection.php");

if (isset($_POST['create-project'])) {
    $userID= $_SESSION['user_id'];
    $projectTitle = trim($_POST['project-title']);

    if (empty($projectTitle)) {
        header("Location: ../view/mainpage.php?msg=Project title cannot be empty");
        exit();
    }

    $sql = "INSERT INTO Projects (name,user_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $projectTitle, $userID);

    if ($stmt->execute()) {
        header("Location: ../view/mainpage.php?msg=Project created successfully");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: ../view/mainpage.php");
    exit();
}
?>
