<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include("../settings/connection.php");
// var_dump($_GET['project_id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_GET['project_id'];

    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        $stmt = $conn->prepare("SELECT * FROM Projects WHERE id = ?");
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $project_result = $stmt->get_result();

        if ($project_result->num_rows > 0) {
            $stmt = $conn->prepare("INSERT INTO user_projects (user_id, project_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $project_id);
            if ($stmt->execute()) {
                echo "Project shared successfully!";
            } else {
                echo "Failed to share the project.";
            }
        } else {
            echo "Project does not exist.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
$conn->close();
