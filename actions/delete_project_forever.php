<?php
include '../settings/connection.php';

function deleteProjectForever($project_id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM archived_projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($project_id > 0) {
    $success = deleteProjectForever($project_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
}
?>