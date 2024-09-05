<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .task-bubble {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 10px;
            margin: 10px;
            width: 200px;
            float: left;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Add Task Form -->
        <h2>Add New Task</h2>
        <form id="addTaskForm" action="../actions/add_task.php?project_id=<?php echo $_GET['project_id']; ?>" method="POST">
            <div class="form-group">
                <label for="taskName">Task Name:</label>
                <input type="text" id="addTaskName" name="taskName" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="addDescription" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="addTask">+ Add Task</button>
            </div>
        </form>

        <!-- Edit Task Form (hidden by default) -->
        <div id="editTaskContainer" class="hidden">
            <h2>Edit Task</h2>
            <form id="editTaskForm" action="../actions/edit_task.php" method="POST">
                <div class="form-group">
                    <label for="taskName">Task Name:</label>
                    <input type="text" id="editTaskName" name="taskName" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="editDescription" name="description" rows="4" required></textarea>
                </div>
                <input type="hidden" id="editTaskId" name="task_id">
                <input type="hidden" id="editProjectId" name="project_id" value="<?php echo $_GET['project_id']; ?>">
                <div class="form-group">
                    <button type="submit" name="editTask">✎ Edit Task</button>
                </div>
            </form>
        </div>

        <h2>Tasks:</h2>
        <div class="tasks-container">
            <?php
            include '../functions/getTasks.php';
            $tasks = getTasks($_GET['project_id']);
            foreach ($tasks as $task): ?>
                <div class="task-bubble">
                    <h3><?php echo $task['name']; ?></h3>
                    <p><?php echo $task['description']; ?></p>
                    <button class="edit-button" 
                            data-id="<?php echo $task['id']; ?>"
                            data-name="<?php echo $task['name']; ?>"
                            data-description="<?php echo $task['description']; ?>">
                        &#9997; Edit
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <script>
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.getAttribute('data-id');
                const taskName = this.getAttribute('data-name');
                const taskDescription = this.getAttribute('data-description');

                // Populate the Edit Task form fields
                document.getElementById('editTaskName').value = taskName;
                document.getElementById('editDescription').value = taskDescription;
                document.getElementById('editTaskId').value = taskId;

                // Show the Edit Task form and hide the Add Task form
                document.getElementById('editTaskContainer').classList.remove('hidden');
                document.getElementById('addTaskForm').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
