<?php
// Start the session
session_start();

// Include your database connection
require_once '../settings/connection.php';  
include '../functions/getProjects.php';
include '../functions/get_sharedProjects.php';

$projects = getProjects($userID);
if ($projects === false) {
    echo "Error: Unable to fetch projects.";
    exit();
}

// $userID = $_GET['userID'];
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: ../login/login.php");
    exit();
}

$projectID = $_GET['project_id'];


// var_dump($userID);exit;
$sql = "SELECT premium FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($premium);
$stmt->fetch();
$stmt->close();

if ($premium) {
    // User is premium, redirect to share page
    $redirectUrl = '../view/share.php?project_id=' . htmlspecialchars($projectID);
    header("Location: $redirectUrl");
    exit();
} else {
    // User is not premium, redirect to mainpage 
        header('Location: ../view/mainpage.php?msg=User must first be a premium User');

    exit();
}
?>
