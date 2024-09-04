<?php
session_start();

include("../settings/connection.php");

function getProjects($userId) {
    global $conn;
    $sql = "SELECT name FROM projects WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row['name'];
    }
    return $projects;
}

$id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel="stylesheet" href="../css/task.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header style="background-color: beige; height: 50px;">
        <h1 id="welcome-message" style="display: inline-block; margin: 0 auto; font-size: 28px;">Tasks!</h1>
        <a href="../login/logout.php" style="float: right; color: black; text-decoration: underline; font-size: 24px;">
            <i class="fas fa-sign-in-alt"></i> Logout
        </a>
    </header>

    <!-- Task Form -->
    <div class="task-form-container">
        <h2>Add Task</h2>
        <form action="../actions/edit_task.php" method="POST">
            <input type="text" id="task-name" name="taskName" placeholder="Task Name" required>
            <input type="text" id="task-desc" name="description" placeholder="Task Description" required>
            <button type="submit" id="create-task" name="addTask">Add Task</button>
        </form>
    </div>

    <!-- Project Container -->
    <div class="project-container">
        <?php
            $projects = getProjects($id);
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    echo "<div class='project-bubble'>$project</div>";
                }
            } else {
                echo "<p>No projects found.</p>";
            }
        ?>
    </div>

    <script src="../js/task.js"></script>
</body>
</html>
