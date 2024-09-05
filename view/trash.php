<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php';

// Check if user is logged in
$userID = $_SESSION['user_id'];
if (!$userID) {
    header("Location: login.php");
    exit();
}

// Function to get user name
function getUserName($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return $row['firstname'] . ' ' . $row['lastname'];
    }
    return null;
}

// Fetch user name
$userName = getUserName($userID);

// Function to get archived projects
function getArchivedProjects($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, name FROM archived_projects WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    $stmt->close();
    return $projects;
}

// Function to get archived tasks
function getArchivedTasks($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, name, project_id FROM archived_tasks WHERE project_id IN (SELECT id FROM Projects WHERE user_id = ?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    $stmt->close();
    return $tasks;
}

// Fetch archived projects and tasks
$archivedProjects = getArchivedProjects($userID);
$archivedTasks = getArchivedTasks($userID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trash</title>
    <link rel="stylesheet" href="../css/mainpage.css">
    <style>
        .trash-container {
            margin: 20px;
        }
        .trash-item {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .trash-item h3 {
            margin-top: 0;
        }
        .trash-item button {
            margin-right: 10px;
            padding: 5px 10px;
            border: none;
            color: white;
            cursor: pointer;
        }
        .recover-btn {
            background-color: #28a745;
        }
        .delete-btn {
            background-color: #dc3545;
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
    <div class="trash-container">
        <h2>Archived Projects</h2>
        <?php if (empty($archivedProjects)): ?>
            <p>No archived projects found.</p>
        <?php else: ?>
            <?php foreach ($archivedProjects as $project): ?>
                <div class="trash-item">
                    <h3><?php echo htmlspecialchars($project['name']); ?></h3>
                    <button class="recover-btn" onclick="recoverItem('project', <?php echo $project['id']; ?>)">Recover</button>
                    <button class="delete-btn" onclick="deleteItem('project', <?php echo $project['id']; ?>)">Delete Forever</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="trash-container">
        <h2>Archived Tasks</h2>
        <?php if (empty($archivedTasks)): ?>
            <p>No archived tasks found.</p>
        <?php else: ?>
            <?php foreach ($archivedTasks as $task): ?>
                <div class="trash-item">
                    <h3><?php echo htmlspecialchars($task['name']); ?></h3>
                    <button class="recover-btn" onclick="recoverItem('task', <?php echo $task['id']; ?>)">Recover</button>
                    <button class="delete-btn" onclick="deleteItem('task', <?php echo $task['id']; ?>)">Delete Forever</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
function recoverItem(type, id) {
    if (confirm('Are you sure you want to recover this item?')) {
        fetch(`../actions/recover_${type}.php?id=${id}`, {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert('Error recovering item.');
            }
        })
        .catch(error => {
            console.error('Error recovering item:', error);
            alert('Error recovering item.');
        });
    }
}

function deleteItem(type, id) {
    if (confirm('Are you sure you want to delete this item forever?')) {
        fetch(`../actions/delete_${type}_forever.php?id=${id}`, {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert('Error deleting item forever.');
            }
        })
        .catch(error => {
            console.error('Error deleting item forever:', error);
            alert('Error deleting item forever.');
        });
    }
}
</script>

</body>
</html>