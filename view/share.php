<?php
session_start();
include '../settings/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Project</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Share Project</h2>

        <form id="shareProjectForm" action="../actions/share_project.php?project_id=<?php echo htmlspecialchars($projectID); ?>" method="POST">
            <label for="email">Share with (Email):</label>
            <input type="email" id="email" name="email" required>

            <input type="submit" value="Share">
        </form>
    </div>

    <script>
        // Function to get the project_id from the URL
        function getProjectIDFromURL() {
            const params = new URLSearchParams(window.location.search);
            return params.get('project_id');
        }

        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', (event) => {
            const projectID = getProjectIDFromURL();
            if (projectID) {
                // Corrected line to set the action attribute
                document.getElementById('shareProjectForm').action = `../actions/share_project.php?project_id=${projectID}`;
            } else {
                alert('Project ID is missing from the URL.');
            }
        });
    </script>
</body>
</html>
