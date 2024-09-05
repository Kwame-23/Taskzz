<?php
include("../settings/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = intval($_POST['taskId']);
    $completed = intval($_POST['completed']);

    $stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $stmt->bind_param("ii", $completed, $taskId);

    if ($stmt->execute()) {
        echo "Task completion status updated.";
    } else {
        echo "Error updating task status: " . $conn->error;
    }

    $stmt->close();
}
?>
