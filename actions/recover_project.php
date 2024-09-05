<?php
include '../settings/connection.php';

// Function to log messages to a file
function log_message($message) {
    $logFile = '../logs/recover_project.log'; // Adjust path as needed
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . PHP_EOL, FILE_APPEND);
}

function recoverProject($project_id) {
    global $conn;

    // Start a transaction
    $conn->begin_transaction();
    log_message("Starting transaction for project ID: $project_id");

    try {
        // Prepare and execute query to move project from the archive table to the original Projects table
        $stmt = $conn->prepare("INSERT INTO Projects (id, name, user_id)
            SELECT id, name, user_id FROM archived_projects WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $affectedRows = $stmt->affected_rows;
        log_message("Executed insert query for project ID: $project_id. Rows affected: $affectedRows");

        // Check if the project was successfully recovered
        if ($affectedRows > 0) {
            log_message("Project ID $project_id successfully inserted into Projects table.");

            // Delete the project from the archive table
            $stmt = $conn->prepare("DELETE FROM archived_projects WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $project_id);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            log_message("Executed delete query for project ID: $project_id. Rows affected: $affectedRows");

            // Commit transaction
            $conn->commit();
            log_message("Transaction committed for project ID: $project_id");

            return $affectedRows > 0;
        } else {
            // Rollback transaction if no rows were affected
            $conn->rollback();
            log_message("No rows affected. Transaction rolled back for project ID: $project_id");
            return false;
        }
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollback();
        log_message("Exception occurred: " . $e->getMessage());
        return false;
    }
}

$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ($project_id > 0) {
    $success = recoverProject($project_id);
    echo json_encode(['success' => $success]);
    log_message("Recover request completed for project ID: $project_id. Success: " . ($success ? 'true' : 'false'));
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
    log_message("Invalid project ID: $project_id");
}
?>