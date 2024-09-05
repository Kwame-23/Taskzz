<?php
// Include necessary files
include '../settings/connection.php';
include '../functions/getTasks.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get project ID from request
$projectID = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

// Validate project ID
if ($projectID <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
    exit();
}

// Check if user is logged in
session_start();
$userID = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
if ($userID <= 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Fetch tasks for the project
$tasks = getTasks($userID, $projectID);
if ($tasks === false) {
    echo json_encode(['success' => false, 'message' => 'Unable to fetch tasks.']);
    exit();
}

// Return tasks in JSON format
echo json_encode(['success' => true, 'tasks' => $tasks]);
?>