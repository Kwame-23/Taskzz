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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #ffff;
    width: 100vw; /* Set the width to 100 viewport width */
    margin: 0 auto; /* Center the body horizontally */
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden
}

header {
    background-color: #fff;
    padding-top: 50px;
    margin-top: 30px;
    padding-bottom: 0px;
    display: flex;
    align-items: center;
    justify-content: space-between; /* Align content to sides */
    padding: 0 50px; /* Add padding for spacing */
}

.logo {
    height: 100px;
    margin-left: 50px;
}

h2 {
    font-size: 45px;
    font-family: 'Inter', sans-serif;
    font-weight: 100;
}

.profile {
    display: flex;
    align-items: center;
    gap: 15px; /* Add space between profile items */
}

.profile span {
    margin-right: 10px;
}

.logout, .trash {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 30px;
    cursor: pointer;
    margin-right: 10px;
}

.logout:hover, .trash:hover {
    background-color: #218838;
}

main {
    padding: 20px;
    text-align: left;
    margin-left: 200px;
}

.welcome-message {
    text-align: center;
    margin-bottom: 20px;
    margin-left: -30px;
}

.project .project-btn {
    width: 100%;
    text-align: left;
    color: black;
    border: none;
    padding: 10px;
    cursor: pointer;
    border-radius: 5px;
}

.tasks-dropdown {
    display: none; /* Hide tasks initially */
    margin-top: 10px;
}

.project .add-task-btn {
    width: 100%;
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
}

.project .add-task-btn:hover {
    background-color: #0056b3;
}

.no-projects {
    text-align: center;
    padding: 20px;
}

.no-projects img {
    width: 200px;
    margin: 0 auto;
    display: block;
}

/* Floating Action Button */
.fab {
    position: fixed;
    bottom: 100px;
    right: 20px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #28a745;
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
    background-color: #218838;
}

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

.projects-container {
    flex-wrap: wrap; /* Allow projects to wrap onto new lines if necessary */
    justify-content: space-between; /* Spread out the projects evenly */
    gap: 70px; /* Adjust the space between project boxes */
    max-width: 1000px; /* Adjust the container width */
    margin: 0 auto; /* Center the container */
}

.project {
    background-color: #fff;
    padding: 50px;
    border-radius: 5px;
    border-color: black;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 30px); /* Ensure exactly 3 projects per row */
    box-sizing: border-box; /* Include padding and border in width calculation */
}

/* Media Query for Smaller Screens */
@media (max-width: 1024px) {
    .project {
        width: calc(50% - 20px); /* 2 projects per row on medium screens */
    }
}

@media (max-width: 768px) {
    .project {
        width: calc(100% - 20px); /* 1 project per row on small screens */
    }
}

footer {
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #fff;
    border-top: 5px solid #28a745; /* Green top border */
    text-align: center;
    padding: 10px;
    font-size: 14px;
    margin-top: 100px;
    z-index: 0;
}


    </style>
</head>
<body>

<header>
    <img src="../images/listaviva-2.svg" alt="Logo" class="logo">
    <div class="profile">
        <span><?php echo htmlspecialchars($userName); ?></span>
        <button onclick="location.href='../view/trash.php'" class="logout">Trash</button>
        <button onclick="location.href='../actions/logout.php'" class="logout">Logout</button>
        
    </div>
</header>

<main>
    <div class="welcome-message">
        <h2>Welcome, <?php echo htmlspecialchars($userName); ?> 👋</h2>
    </div>
</main>

<section>
    <?php if (empty($projects)): ?>
        <div class="no-projects">
            <img src="../images/no-task.png" alt="No Projects Found">
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
                    <div id="tasks-<?php echo htmlspecialchars($project['id']); ?>" class="tasks-dropdown">
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
            </section>


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

<footer>
    <p>&copy; 2024 Your Company Name. All rights reserved.</p>
</footer>

<script>
// JavaScript functions remain unchanged
function toggleDropdown(projectID) {
    const dropdown = document.getElementById('tasks-' + projectID);
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none'; // Hide if already visible
        return;
    }

    // Fetch tasks and display
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
                
                dropdown.style.display = 'block'; // Show tasks dropdown
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

