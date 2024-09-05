<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php';
include '../functions/getProfile.php'; // Ensure this file contains getFName()
include '../functions/getProjects.php';
include '../functions/getTasks.php';

// Check if user is logged in
$userID = $_SESSION['user_id'] ?? null;
if (!$userID) {
    error_log("User not logged in. Redirecting to login.");
    header("Location: login.php");
    exit();
}

// Fetch user data
$userName = getFName($userID); // Function to fetch the user's first name
if ($userName === "User not found" || empty($userName)) {
    error_log("Error: Unable to fetch user name.");
    echo "Error: Unable to fetch user name.";
    exit();
}

$taskID = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;
$isEdit = $taskID !== null;

if ($isEdit) {
    // Fetch task details for editing
    $stmt = $conn->prepare("SELECT * FROM Tasks WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        echo "Error preparing statement.";
        exit();
    }

    $stmt->bind_param("i", $taskID);
    $stmt->execute();
    $task = $stmt->get_result()->fetch_assoc();

    if (!$task) {
        error_log("Error: Task not found with ID " . $taskID);
        echo "Error: Task not found.";
        exit();
    }
}

// Fetch projects for the project selection dropdown
$projects = getProjects($userID);
if ($projects === false) {
    error_log("Error fetching projects.");
    echo "Error fetching projects.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = trim($_POST['task-name']);
    $taskDescription = trim($_POST['task-description']);
    $taskCompleted = isset($_POST['task-completed']) ? 1 : 0;
    $projectID = isset($_POST['project-id']) ? intval($_POST['project-id']) : null;

    if (empty($taskName) || empty($projectID)) {
        error_log("Error: Task name and project ID are required.");
        echo "Error: Task name and project ID are required.";
        exit();
    }

    if ($isEdit) {
        // Update task
        $sql = "UPDATE tasks SET name = ?, description = ?, completed = ?, project_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            echo "Error preparing statement.";
            exit();
        }

        $stmt->bind_param("ssiii", $taskName, $taskDescription, $taskCompleted, $projectID, $taskID);
    } else {
        // Add new task
        $sql = "INSERT INTO tasks (name, description, completed, project_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare statement failed: " . $conn->error);
            echo "Error preparing statement.";
            exit();
        }

        $stmt->bind_param("ssii", $taskName, $taskDescription, $taskCompleted, $projectID);
    }

    if ($stmt->execute()) {
        $action = $isEdit ? "updated" : "added";
        error_log("Task $action successfully. Redirecting to mainpage.php.");
        header("Location: ../view/mainpage.php?msg=Task $action successfully");
        exit();
    } else {
        error_log("Error executing query: " . $stmt->error);
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit Task' : 'Add Task'; ?></title>
    <link rel="stylesheet" href="../css/mainpage.css">
</head>
<body>

<header>
    <img src="../images/logo.png" alt="Logo" class="logo">
    <div class="profile">
        <img src="../images/profile.png" alt="Profile Picture" class="avatar">
        <span>Welcome, <?php echo htmlspecialchars($userName); ?>!</span>
        <button onclick="location.href='../login/logout.php'" class="logout">Logout</button>
    </div>
</header>

<main>
    <h1><?php echo $isEdit ? 'Edit Task' : 'Add Task'; ?></h1>
    <form action="task.php<?php echo $isEdit ? '?task_id=' . $taskID : ''; ?>" method="post">
        <label for="task-name">Task Name:</label>
        <input type="text" id="task-name" name="task-name" value="<?php echo $isEdit ? htmlspecialchars($task['name'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?>" required>

        <label for="task-description">Description:</label>
        <textarea id="task-description" name="task-description"><?php echo $isEdit ? htmlspecialchars($task['description'] ?? '', ENT_QUOTES, 'UTF-8') : ''; ?></textarea>

        <label for="task-completed">Completed:</label>
        <input type="checkbox" id="task-completed" name="task-completed" <?php echo $isEdit && !empty($task['completed']) ? 'checked' : ''; ?>>

        <label for="project-id">Project:</label>
        <select id="project-id" name="project-id" required>
            <option value="">Select Project</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?php echo $project['id']; ?>" <?php echo $isEdit && $task['project_id'] == $project['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($project['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"><?php echo $isEdit ? 'Update Task' : 'Add Task'; ?></button>
    </form>
</main>

</body>
</html>