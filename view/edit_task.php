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

        .trash-icon {
            text-align: right;
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
        <img src="../images/listaviva-2.svg" alt="Logo" class="logo">
        <div class="profile">
            <img src="../images/profile.png" alt="Profile Picture" class="avatar">
            <span>Welcome, <?php echo htmlspecialchars($userName); ?>!</span>
            <button onclick="location.href='../actions/logout.php'" class="logout">Logout</button>
        </div>
    </header>

    <h2 style="display: flex; justify-content: space-between; align-items: center; margin: 0 20px;">
    <span>Edit Task: <?php echo htmlspecialchars($task['name']); ?></span>
    <a href="../actions/soft_delete_task.php?task_id=<?php echo htmlspecialchars($taskID); ?>" class="trash-icon" title="Move to Trash" style="text-decoration: none; font-size: 1.5em; margin-left: auto;">
        🗑️
    </a>
</h2>

    <main>
        <form action="../actions/update_task.php" method="post">
            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($taskID); ?>">
            <input type="text" name="task_name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
            <textarea name="task_description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
            <input type="checkbox" name="task_completed" <?php echo $task['completed'] ? 'checked' : ''; ?>> Completed
            <button type="submit" name="update_task">Update Task</button>
        </form>

        
    </main>
</body>
</html>