<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userID = $_SESSION['user_id'];

// Get the project ID from the request
$projectID = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ($projectID <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID']);
    exit();
}

// Fetch tasks associated with the project
$stmt = $conn->prepare("SELECT id, name, description, completed FROM tasks WHERE project_id = ?");
$stmt->bind_param("i", $projectID);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
$stmt->close();

echo json_encode(['success' => true, 'tasks' => $tasks]);
?>