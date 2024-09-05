<?php
include '../settings/connection.php';

function getTasks($project_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT name, description FROM tasks WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = [
            'name' => $row['name'],
            'description' => $row['description']
        ];
    }

    $stmt->close();
    return $tasks;
}
