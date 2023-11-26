<?php
require_once('./dao/taskDAO.php');
require_once('./dao/userDAO.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get the logged-in user
$userDAO = new UserDAO();
$user = $userDAO->getUserById($_SESSION['user_id']);

if (!isset($_GET['taskId']) || !is_numeric($_GET['taskId'])) {
    // Send the user back to the main page
    header("Location: index.php");
    exit;
} else {
    $taskDAO = new TaskDAO();
    $task = $taskDAO->getTask($_GET['taskId']);

    // Check if the task exists and if the logged-in user is authorized to edit it
    if ($task && $task['user_id'] == $user['id']) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>Edit Task - <?php echo htmlspecialchars($task->getTaskName()); ?></title>
            <script type="text/javascript">
                function confirmDelete(taskName){
                    return confirm("Do you wish to delete " + taskName + "?");
                }
            </script>
        </head>
        <body>

            <?php
                if(isset($_GET['recordsUpdated'])){
                        if(is_numeric($_GET['recordsUpdated'])){
                            echo '<h3> '. $_GET['recordsUpdated']. ' Task Record Updated.</h3>';
                        }
                }
                if(isset($_GET['missingFields'])){
                        if($_GET['missingFields']){
                            echo '<h3 style="color:red;"> Please enter all required data.</h3>';
                        }
                }
            ?>
            <!-- Edit Task Form -->
            <h3>Edit Task</h3>
            <form name="editTask" method="post" action="process_task.php?action=edit">
                <!-- Display Task Details -->
                <table>
                    <tr>
                        <td>Task ID:</td>
                        <td>
                            <input type="hidden" name="taskId" id="taskId" value="<?php echo htmlspecialchars($task->getTaskId()); ?>">
                            <?php echo htmlspecialchars($task->getTaskId()); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Task Name:</td>
                        <td><input type="text" name="taskName" id="taskName" value="<?php echo htmlspecialchars($task->getTaskName()); ?>"></td>
                    </tr>
                    <tr>
                        <td>Priority:</td>
                        <td><input type="text" name="priority" id="priority" value="<?php echo htmlspecialchars($task->getPriority()); ?>"></td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td><input type="date" name="dueDate" id="dueDate" value="<?php echo htmlspecialchars($task->getDueDate()); ?>"></td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td><input type="text" name="status" id="status" value="<?php echo htmlspecialchars($task->getStatus()); ?>"></td>
                    </tr>
                    <tr>
                        <!-- Add more fields as needed -->
                        <td><input type="submit" name="btnSubmit" id="btnSubmit" value="Update Task"></td>
                        <td><input type="reset" name="btnReset" id="btnReset" value="Reset"></td>
                    </tr>
                </table>
            </form>
            <h4><a href="index.php">Back to main page</a></h4>
        </body>
        </html>
    <?php
    } else {
        // User is not authorized to edit this task
        header("Location: index.php?unauthorized=true");
        exit;
    }
}
?>
