<?php
// Include the connection file to use the $conn variable
include '../settings/connection.php';

function getTaskById($taskID) {
    global $conn; // Use the $conn variable from connection.php

    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}
?>