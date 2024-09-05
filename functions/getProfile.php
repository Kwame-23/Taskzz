<?php
include '../settings/connection.php';

function getFName($user_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT firstname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["firstname"];
    } else {
        $name = "User not found";
    }

    $stmt->close();
    return $name; // Return the name instead of echoing it
}
