<?php
include '../settings/connection.php';

function deleteTaskForever($task_id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM archived_tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($task_id > 0) {
    $success = deleteTaskForever($task_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid task ID.']);
}
?>