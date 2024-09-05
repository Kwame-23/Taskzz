<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../settings/connection.php';
include '../functions/getProfile.php';
include '../functions/getProjects.php';
include '../functions/getTasks.php';

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

// Fetch user's projects
$projects = getProjects($userID);
if ($projects === false) {
    echo "Error: Unable to fetch projects.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Console</title>
    <link rel="stylesheet" href="../css/mainpage.css">
    <style>
        /* Add your CSS styles here */
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            font-size: 24px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .fab:hover {
            background-color: #0056b3;
        }
        .projects-container {
            margin: 20px;
        }
        .project {
            margin-bottom: 20px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .project-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            width: 80%;
            text-align: left;
        }
        .trash-icon {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 16px;
        }
        .trash-icon:hover {
            background: #c82333;
        }
        .tasks-dropdown {
            display: none;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            position: absolute;
            width: calc(100% - 20px);
            left: 10px;
            top: 100%;
            z-index: 1;
        }
        .tasks-dropdown.show {
            display: block;
        }
        .task {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .task input[type="checkbox"] {
            margin-right: 10px;
        }
        .edit-task-link {
            margin-left: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .edit-task-link:hover {
            text-decoration: underline;
        }
        .add-task-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            display: block;
            margin-top: 10px;
        }
        .add-task-btn:hover {
            background: #218838;
        }
        .no-projects {
            text-align: center;
        }
        .no-projects img {
            max-width: 100%;
            height: auto;
        }
        /* Popup styles */
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }
        .popup-content input {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .popup-content button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .popup-content button:hover {
            background: #0056b3;
        }
        .popup-content .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
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
    <?php if (empty($projects)): ?>
        <div class="no-projects">
            <img src="../images/no-projects.png" alt="No Projects Found">
            <p>No projects found.</p>
        </div>
    <?php else: ?>
        <div class="projects-container">
            <?php foreach ($projects as $project): ?>
                <div class="project">
                    <button class="project-btn" onclick="toggleDropdown('<?php echo htmlspecialchars($project['id']); ?>')">
                        <?php echo htmlspecialchars($project['name']); ?>
                    </button>
                    <button class="trash-icon" onclick="confirmDeleteProject(<?php echo htmlspecialchars($project['id']); ?>)">🗑️</button>
                    <div id="<?php echo htmlspecialchars($project['id']); ?>" class="tasks-dropdown">
                        <!-- Tasks will be dynamically loaded here -->
                        <div class="no-tasks">
                            <p>Select this project to view tasks.</p>
                        </div>
                        <!-- Add Task button will be appended dynamically -->
                        <button class="add-task-btn" onclick="location.href='../view/task.php?project_id=<?php echo htmlspecialchars($project['id']); ?>'">Add Task</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Floating Action Button to Add Project -->
<button class="fab" onclick="openPopup()">+</button>

<!-- Popup for Adding Project -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Add New Project</h2>
        <form id="addProjectForm" action="../actions/add_project.php" method="post">
            <input type="text" name="project-title" placeholder="Enter project title" required>
            <button type="submit" name="create-project">Add Project</button>
        </form>
    </div>
</div>

<script>
function toggleDropdown(projectID) {
    const dropdown = document.getElementById(projectID);
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        return;
    }

    fetch(`../actions/fetch_tasks.php?project_id=${projectID}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const taskContainer = dropdown;
                taskContainer.innerHTML = ''; // Clear existing content
                
                if (data.tasks.length > 0) {
                    data.tasks.forEach(task => {
                        const taskDiv = document.createElement('div');
                        taskDiv.classList.add('task');
                        
                        taskDiv.innerHTML = `
                            <input type="checkbox" ${task.completed ? 'checked' : ''} onchange="toggleTaskCompleted(${task.id})">
                            <span class="task-name">${task.name}</span>
                            <span class="task-desc">${task.description.substr(0, 10)}...</span>
                            <a href="../view/edit_task.php?task_id=${task.id}" class="edit-task-link">Edit</a>
                        `;
                        
                        taskContainer.appendChild(taskDiv);
                    });
                } else {
                    taskContainer.innerHTML = '<p>No tasks found for this project.</p>';
                }
                
                // Always add the "Add Task" button
                const addTaskButton = document.createElement('button');
                addTaskButton.classList.add('add-task-btn');
                addTaskButton.textContent = 'Add Task';
                addTaskButton.onclick = () => location.href = `../view/task.php?project_id=${projectID}`;
                
                taskContainer.appendChild(addTaskButton);
                
                dropdown.classList.add('show');
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching tasks:', error);
            alert('Error fetching tasks.');
        });
}

function toggleTaskCompleted(taskID) {
    fetch(`../actions/toggle_task.php?task_id=${taskID}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Error updating task status.');
            }
        })
        .catch(error => {
            console.error('Error updating task status:', error);
            alert('Error updating task status.');
        });
}

function confirmDeleteProject(projectID) {
    if (confirm('Are you sure you want to delete this project?')) {
        fetch(`../actions/delete_project.php?project_id=${projectID}`, {
            method: 'POST',
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page to reflect the changes
            } else {
                alert('Error deleting project.');
            }
        })
        .catch(error => {
            console.error('Error deleting project:', error);
            alert('Error deleting project.');
        });
    }
}

function openPopup() {
    document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}
</script>
</body>
</html>