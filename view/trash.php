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
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 150px;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .logout {
            background-color: #2ecc71;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .logout:hover {
            background-color: #27ae60;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
            border-bottom: 2px solid #dedede;
            padding-bottom: 10px;
        }

        .trash-container {
            margin-bottom: 40px;
        }

        .trash-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #dedede;
        }

        .trash-item h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .trash-item button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: white;
        }

        .recover-btn {
            background-color: #2ecc71;
        }

        .recover-btn:hover {
            background-color: #27ae60;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .trash-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .trash-item button {
                margin-top: 10px;
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

<main>
    <!-- Archived Projects Section -->
    <div class="trash-container">
        <h2>Archived Projects</h2>
        <?php if (empty($archivedProjects)): ?>
            <p>No archived projects found.</p>
        <?php else: ?>
            <?php foreach ($archivedProjects as $project): ?>
                <div class="trash-item">
                    <h3><?php echo htmlspecialchars($project['name']); ?></h3>
                    <div>
                        <button class="recover-btn" onclick="recoverItem('project', <?php echo $project['id']; ?>)">Recover</button>
                        <button class="delete-btn" onclick="deleteItem('project', <?php echo $project['id']; ?>)">Delete Forever</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Archived Tasks Section -->
    <div class="trash-container">
        <h2>Archived Tasks</h2>
        <?php if (empty($archivedTasks)): ?>
            <p>No archived tasks found.</p>
        <?php else: ?>
            <?php foreach ($archivedTasks as $task): ?>
                <div class="trash-item">
                    <h3><?php echo htmlspecialchars($task['name']); ?></h3>
                    <div>
                        <button class="recover-btn" onclick="recoverItem('task', <?php echo $task['id']; ?>)">Recover</button>
                        <button class="delete-btn" onclick="deleteItem('task', <?php echo $task['id']; ?>)">Delete Forever</button>
                    </div>
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
                location.reload();
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
                location.reload();
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
