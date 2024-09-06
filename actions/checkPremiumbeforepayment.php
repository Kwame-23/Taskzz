<?php
// Start the session
session_start();

// Include your database connection
require_once '../settings/connection.php';  



// $userID = $_GET['userID'];
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: ../login/login.php");
    exit();
}

// var_dump($userID);exit;
$sql = "SELECT premium FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($premium);
$stmt->fetch();
$stmt->close();

if ($premium) {
    // User is premium, redirect to mainpage
    header('Location: ../view/mainpage.php');
    exit();
} else {
    // User is not premium, redirect to upgrade page
    $redirectUrl = '../actions/to_premium.php?user_id=' . $userID;
    header("Location: $redirectUrl");
    exit();
}
?>
