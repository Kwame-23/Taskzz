<?php
include '../settings/connection.php';

function recoverTask($task_id) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO tasks (id, name, description, completed, project_id) SELECT id, name, description, completed, project_id FROM archived_tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt = $conn->prepare("DELETE FROM archived_tasks WHERE id = ?");
        $stmt->bind_param("i", $task_id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    return false;
}

$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($task_id > 0) {
    $success = recoverTask($task_id);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid task ID.']);
}
?>