<?php
include("../settings/connection.php");

if (isset($_POST['taskId']) && isset($_POST['completed'])) {
    $taskId = (int)$_POST['taskId'];
    $completed = $_POST['completed'] === 'true' ? 1 : 0;

    $stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $stmt->bind_param("ii", $completed, $taskId);

    if ($stmt->execute()) {
        echo "Task status updated successfully.";
    } else {
        echo "Error updating task status: " . $conn->error;
    }
}
?>
