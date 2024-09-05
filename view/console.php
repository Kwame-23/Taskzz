<?php
session_start();
include("../functions/getProfile.php");
include("../functions/getProjects.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user's first name
$userName = getFName($userId);

// If the user's name cannot be fetched, log an error and exit (or handle appropriately)
if (empty($userName)) {
    error_log("Error: User name could not be fetched for user ID " . htmlspecialchars($userId));
    $userName = "Guest";  // Fallback to a default value or handle as needed
}

$projects = getProjects($userId); // Fetch user's projects

// If projects cannot be fetched, handle appropriately (logging or showing a message in the UI)
if ($projects === false) {
    error_log("Error: Projects could not be fetched for user ID " . htmlspecialchars($userId));
    $projects = [];  // Fallback to an empty array or handle as needed
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Console</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Header Section -->
    <div>
        <img src="../images/logo.png" alt="Logo" height="50">
        <h1 id="welcome-message">Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
        <div>
            <img src="../images/profile.jpg" alt="Profile Picture">
            <a href="../login/logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Project Section -->
    <div>
        <?php if (empty($projects)) : ?>
            <p>No projects found.</p>
            <img src="../images/no-projects.png" alt="No Projects Found">
        <?php else : ?>
            <?php foreach ($projects as $project) : ?>
                <div data-project-id="<?php echo $project['id']; ?>">
                    <?php echo htmlspecialchars($project['name']); ?>
                    <div class="task-dropdown">
                        <!-- Task List Will Be Dynamically Loaded Here -->
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Floating Action Button to Add a Project -->
    <button onclick="togglePopup()">+</button>

    <!-- The popup screen -->
    <div id="popup-screen">
        <div>
            <form action="../actions/add_project.php" method="POST">
                <h2>Create New Project</h2>
                <input type="text" id="project-title" name="project-title" placeholder="Enter project title" required>
                <button type="submit" id="create-project" name="create-project">Create</button>
                <button type="button" id="cancel" onclick="togglePopup()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function togglePopup() {
            $('#popup-screen').toggle(); // Ensures popup visibility toggles correctly
        }

        $(document).ready(function() {
            $('.project-bubble').on('click', function() {
                var projectId = $(this).data('project-id');
                var taskDropdown = $(this).find('.task-dropdown');

                // Toggle dropdown
                taskDropdown.toggle();

                // Fetch tasks via AJAX if dropdown is opened
                if (taskDropdown.is(':visible')) {
                    $.ajax({
                        url: '../actions/get_tasks.php',
                        type: 'GET',
                        data: { projectId: projectId },
                        success: function(response) {
                            var tasks = JSON.parse(response);
                            taskDropdown.html(''); // Clear the dropdown content
                            $.each(tasks, function(index, task) {
                                var taskHtml = `
                                    <div class="task-item">
                                        <input type="checkbox" class="task-checkbox" data-task-id="${task.id}" ${task.completed ? 'checked' : ''}>
                                        <span>${task.name} - ${task.description.substring(0, 10)}</span>
                                        <button onclick="window.location.href='edit_task.php?taskId=${task.id}'">Edit</button>
                                    </div>
                                `;
                                taskDropdown.append(taskHtml);
                            });

                            taskDropdown.append('<button onclick="showAddTaskForm('+ projectId +')">Add Task</button>');
                        }
                    });
                }
            });

            // AJAX to toggle task completion
            $(document).on('change', '.task-checkbox', function() {
                var taskId = $(this).data('task-id');
                var isChecked = $(this).is(':checked');
                $.post('../actions/toggle_task.php', { taskId: taskId, completed: isChecked }, function(response) {
                    alert(response);
                });
            });
        });

        function showAddTaskForm(projectId) {
            // Logic to show task adding form dynamically
        }
    </script>
</body>
</html>