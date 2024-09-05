<?php
include '../settings/connection.php';

function softDeleteProject($project_id) {
    global $conn;

    // Prepare and execute query to move project to the archive table
    $stmt = $conn->prepare("INSERT INTO archived_projects (id, name, user_id) SELECT id, name, user_id FROM Projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    // Check if the project was successfully archived
    if ($stmt->affected_rows > 0) {
        // Delete the project from the original table
        $stmt = $conn->prepare("DELETE FROM Projects WHERE id = ?");
        $stmt->bind_param("i", $project_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    return false;
}

$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ($project_id > 0) {
    $success = softDeleteProject($project_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
}
?>