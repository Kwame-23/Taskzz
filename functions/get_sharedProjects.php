<?php
include '../settings/connection.php';

function get_sharedProjects($user_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT P.id, P.name 
        FROM Projects P
        JOIN user_projects UP ON P.id = UP.project_id
        WHERE UP.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $sharedProjects = [];
    while($row = $result->fetch_assoc()) {
        $sharedProjects[] = $row;
    }

    $stmt->close();
    return $sharedProjects;
}

// $user_id = $_GET['user_id']; 
// $projects = get_sharedProjects($user_id);

// header('Content-Type: application/json');
// echo json_encode($projects);
