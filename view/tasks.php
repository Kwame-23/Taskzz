<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Deedz</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <!-- The container for the bubbles -->
    <div class="bubble-container">
        <!-- Each bubble will be a list item -->
        <ul>
            <li class="bubble">
                <!-- The checkbox on the left -->
                <input type="checkbox" id="bubble-1">
                <!-- The bubble content -->
                <div class="bubble-content">
                    <!-- The two icons on the right -->
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </div>
            </li>
            <!-- Add more list items for each bubble -->
        </ul>
    </div>

    <!-- The add button and form at the bottom right -->
    <div class="add-button-container">
        <button id="add-button">Add</button>
        <form id="add-form" action="../actions/add_task.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description">
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        document.getElementById('add-button').addEventListener('click', function() {
            var form = document.getElementById('add-form');
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>
