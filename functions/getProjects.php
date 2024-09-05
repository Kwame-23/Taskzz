<?php
include '../settings/connection.php';

function getProjects($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, name FROM Projects WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $projects = [];
    while($row = $result->fetch_assoc()) {
        $projects[] = $row; // Now contains both 'id' and 'name'
    }

    $stmt->close();
    return $projects;
}