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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mainpage.css">
    <style>
        /* General styling */
body {

    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #ffff;
}

header {
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    background-color: #FFFFFF;
    border-bottom: 1px solid #dedede;
    align-items: center;
}

.logo {
    width: 150px;
}

.profile {
    display: flex;
    align-items: center;
}

.profile .avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 10px;
}

.logout {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

main {
    max-width: 1200px;
    margin: 10px auto;
    padding: 10px;
    background-color: #FFFFFF;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

h2 {
    font-size: 24px;
    font-weight:100;
    margin-top: 40px;
    color: #2ecc71;
}

form {
    width: 50%;
}

form label {
    font-size: 18px;
    color: #333;
    display: block;
    margin-bottom: 10px;
}

form input[type="text"],
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

form input[type="checkbox"] {
    margin-right: 10px;
}

form button {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
}

form button:hover {
    background-color: #27ae60;
}

.image-section {
    width: 40%;
    text-align: center;
}

.image-section img {
    width: 100%;
}

/* Specific for the "Back" button */
.back-button {
    background-color: #2ecc71;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 18px;
}

.back-button:hover {
    background-color: #27ae60;
}

/* Responsive styling */
@media screen and (max-width: 768px) {
    main {
        flex-direction: column;
    }

    form {
        width: 100%;
    }

    .image-section {
        display: none;
    }
}


    </style>
</head>
<body>

<header>
    <img src="../images/listaviva-2.svg" alt="Logo" class="logo">
    <div class="profile">
        <img src="../images/profile.png" alt="Profile Picture" class="avatar">
        <span>Welcome, <?php echo htmlspecialchars($userName); ?>!</span>
        <button onclick="location.href='../login/logout.php'" class="logout">Logout</button>
    </div>
</header>

<h2 style="margin-left: 40px; font-weight: 4px;"><?php echo $isEdit ? 'Edit Task' : 'Create A Task'; ?></h2>
<main>
    
    
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

    <div class="image-section">
    <img src="../images/task.png" alt="Illustration of task creation">
</div>

</main>



</body>
</html>