<!DOCTYPE html>
<html>
<head>
    <title>Projectsg</title>
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .project-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .project-bubble {
            background-color: #f0f0f0;
            border-radius: 15px;
            padding: 15px;
            width: 250px;
            text-align: center;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
            margin-left: 8%;
            margin-top: 2%;
        }
        .project-bubble:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header style="background-color: beige ; height:50px;">
        <h1 id="welcome-message" style="display: inline-block; margin: 0 auto; font-size: 28px;">Welcome, <?php 
            session_start();
            include("../functions/getProfile.php");
            include("../functions/getProjects.php");
            $id=$_SESSION['user_id'];
            getFName($id); 
        ?>!</h1>
        <a href="../login/logout.php" style="float: right; color: black; text-decoration: underline; font-size: 24px;">
            <i class="fas fa-sign-in-alt"></i> Logout
        </a>
    </header>

    <!-- Add Project Button -->
    <button id="add-button">+</button>

    <!-- The popup screen -->
    <div id="popup-screen" class="hidden">
        <div class="popup-content">
            <form action="../actions/add_project.php" method="POST">
                <h2>Create New Project</h2>
                <input type="text" id="project-title" name="project-title" placeholder="Enter project title" required>
                <button type="submit" id="create-project" name="create-project">Create</button>
                <button type="button" id="cancel">Cancel</button>
            </form>
        </div>
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

    <script src="../js/main.js"></script>
</body>
</html>
