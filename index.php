<?php require_once('./dao/taskDAO.php'); 
require_once('./dao/userDAO.php'); // Include the UserDAO file
// Get the current user using the UserDAO
$userDAO = new UserDAO();
$user = $userDAO->getCurrentUser();

if (!$user) {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit;
}?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Management App</title>
        <link rel="stylesheet" href="css/tasks.css">
        <script src="js/tasks.js"></script>
    </head>
    <body>
        <h2>Your Tasks</h2>
        <?php
        try {
            $taskDAO = new TaskDAO();

            $hasError = false;

            $errorMessages = Array();

            if(isset($_POST['taskId']) ||
                isset($_POST['taskName']) || 
                isset($_POST['priority']) ||
                isset($_POST['dueDate']) ||
                isset($_POST['status'])) {

                    if(!is_numeric($_POST['taskId']) || $_POST['taskId'] == ""){
                        $hasError = true;
                        $errorMessages['taskIdError'] = 'Please enter a numeric Task ID.';
                    }

                    if($_POST['taskName'] == ""){
                        $errorMessages['taskNameError'] = "Please enter a task name.";
                        $hasError = true;
                    }

                    if($_POST['priority'] == ""){
                        $errorMessages['priorityError'] = "Please enter a priority level.";
                        $hasError = true;
                    }

                    if($_POST['dueDate'] == ""){
                        $errorMessages['dueDateError'] = "Please enter a due date.";
                        $hasError = true;
                    }

                    if($_POST['status'] == ""){
                        $errorMessages['statusError'] = "Please enter a task status.";
                        $hasError = true;
                    }

                    if(!$hasError){
                        $task = new Task($_POST['taskId'], $_POST['taskName'], $_POST['priority'], $_POST['dueDate'], $_POST['status']);
                        $addSuccess = $taskDAO->addTask($task);
                        echo '<h3>' . $addSuccess . '</h3>';
                    }
                }

                if(isset($_GET['deleted'])){
                    if($_GET['deleted'] == true){
                        echo '<h3>Task Deleted</h3>';
                    }
                }

                ?>
                <div class="task-section">

                    <!-- Task Search and Filter -->
                    <div class="search-filter">
                        <label for="search">Search:</label>
                        <input type="text" id="search" name="search">
                        <label for="filter">Filter by:</label>
                        <select id="filter" name="filter">
                            <option value="all">All</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                    <!-- Task Listing -->
                    <ul id="taskList"></ul>

                    <!-- Task Adding -->
                    <div class="add-task">
                        <h3>Add a Task</h3>
                        <form name="addTask" method="post" action="index.php">
        <table>
            <tr>
                <td>Task Name:</td>
                <td><input type="text" name="taskName" id="taskName">
                    <?php
                    if (isset($errorMessages['taskNameError'])) {
                        echo '<span style=\'color:red\'>' . $errorMessages['taskNameError'] . '</span>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Priority:</td>
                <td><input name="priority" type="text" id="priority">
                    <?php
                    if (isset($errorMessages['priorityError'])) {
                        echo '<span style=\'color:red\'>' . $errorMessages['priorityError'] . '</span>';
                    }
                    ?>
                </td>
            </tr>
            
            <tr>
                <td>Due Date:</td>
                <td><input type="text" name="dueDate" id="dueDate"></td>
            </tr>

            <tr>
                <td>Status:</td>
                <td><input type="text" name="status" id="status">
                    <?php
                    if (isset($errorMessages['statusError'])) {
                        echo '<span style=\'color:red\'>' . $errorMessages['statusError'] . '</span>';
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td><input type="submit" name="btnSubmit" id="btnSubmit" value="Add Task"></td>
                <td><input type="reset" name="btnReset" id="btnReset" value="Reset"></td>
            </tr>
        </table>
    </form>
                    </div>
                </div>


                <?php
            echo '<h1>Task Management System</h1>';
            echo '<h2>Task List</h2>';

            $tasks = $taskDAO->getTasks($user->getId());

            if ($tasks) {
                echo '<table border="1">';
                echo '<tr><th>Task ID</th><th>Task Name</th><th>Priority</th><th>Due Date</th><th>Status</th></tr>';
                foreach ($tasks as $task) {
                    echo '<tr>';
                    echo '<td>' . $task->getTaskId() . '</td>';
                    echo '<td>' . $task->getTaskName() . '</td>';
                    echo '<td>' . $task->getPriority() . '</td>';
                    echo '<td>' . $task->getDueDate() . '</td>';
                    echo '<td>' . $task->getStatus() . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
            // else {
            //     echo '<p>No tasks found.</p>';
            // }
        } catch (Exception $e) {
            echo '<h3>Error on page.</h3>';
            echo '<p>' . $e->getMessage() . '</p>';
        }
        ?>
 </body>
</html>
