<!DOCTYPE html>
<html>
<head>
    <title>Share Project</title>
    <style>
        /* Global styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        /* Form styles */
        #shareProjectForm {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="email"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            font-size: 16px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        /* Add some breathing room */
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <form id="shareProjectForm" action="" method="POST">
        <h2>Share Project</h2>
        <label for="email">Share to (Email):</label>
        <input type="email" id="email" name="email" required><br><br>
       <br> <input type="submit" value="Share">
    </form>

    <script>
        // Function to get the project_id from the URL
        function getProjectIDFromURL() {
            const params = new URLSearchParams(window.location.search);
            return params.get('project_id');
        }

        // Set the form's action attribute with the project_id
        const projectID = getProjectIDFromURL();
        if (projectID) {
            document.getElementById('shareProjectForm').action = `../actions/share_project.php?project_id=${projectID}`;
        } else {
            alert('Project ID is missing from the URL.');
        }
    </script>
</body>
</html>