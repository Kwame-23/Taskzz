<?php
session_start();
include '../settings/connection.php';

?>


<!DOCTYPE html>
<html>
<head>
    <title>Share Project</title>
</head>
<body>
    <h2>Share Project</h2>

    <form id="shareProjectForm" action="../actions/share_project.php?project_id=<?php echo htmlspecialchars($projectID); ?>" method="POST">
        <label for="email">Share with (Email):</label>
        <input type="email" id="email" name="email" required><br><br>

        <input type="submit" value="Share">
    </form>

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
