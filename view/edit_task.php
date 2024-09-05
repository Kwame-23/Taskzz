<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php'; // Ensure this file sets up $conn
include '../functions/getProfile.php';
include '../functions/getProjects.php';
include '../functions/getTasks.php'; // This should include the getTaskById function
include '../functions/updateTask.php'; // This should include the updateTask function

// Check if user is logged in
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$userName = getFName($userID);
if ($userName === "User not found" || empty($userName)) {
    echo "Error: Unable to fetch user name.";
    exit();
}

// Fetch task details if a task ID is set
$taskID = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;
if ($taskID) {
    $task = getTaskById($taskID);
    if (!$task) {
        echo "Error: Task not found.";
        exit();
    }
} else {
    echo "Error: No task ID provided.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../css/mainpage.css">
    <style>
        .trash-icon {
            cursor: pointer;
            color: #dc3545; /* Red color for delete */
            font-size: 24px;
        }
        .trash-icon:hover {
            color: #c82333; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <header>
        <img src="../images/logo.png" alt="Logo" class="logo">
        <div class="profile">
            <img src="../images/profile.png" alt="Profile Picture" class="avatar">
            <span>Welcome, <?php echo htmlspecialchars($userName); ?>!</span>
            <button onclick="location.href='../actions/logout.php'" class="logout">Logout</button>
        </div>
    </header>

    <main>
        <h2>Edit Task: <?php echo htmlspecialchars($task['name']); ?></h2>
        <form action="../actions/update_task.php" method="post">
            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($taskID); ?>">
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
            <textarea name="task_description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            <input type="checkbox" name="task_completed" <?php echo $task['completed'] ? 'checked' : ''; ?>> Completed
            <button type="submit" name="update_task">Update Task</button>
        </form>

        <a href="../actions/soft_delete_task.php?task_id=<?php echo htmlspecialchars($taskID); ?>" class="trash-icon" title="Move to Trash">
            🗑️
        </a>
    </main>
</body>
</html>