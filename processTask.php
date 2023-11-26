<?php
require_once('./dao/taskDAO.php');
require_once('./dao/userDAO.php');

// Assuming you have a user authentication system, you might get the user ID from the session
session_start();
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$userDAO = new UserDAO();
$user = $userDAO->getUserById($_SESSION['userId']);

// Check if the 'action' parameter is set in the URL
if (isset($_GET['action'])) {
    // Check the value of the 'action' parameter
    if ($_GET['action'] == "edit") {
        // Check if all required fields are set in the POST request
        if (
            isset($_POST['taskId']) &&
            isset($_POST['taskName']) &&
            isset($_POST['priority']) &&
            isset($_POST['dueDate']) &&
            isset($_POST['status'])
        ) {
            // Check the validity of the fields
            if (
                is_numeric($_POST['taskId']) &&
                $_POST['taskName'] != "" &&
                $_POST['priority'] != "" &&
                $_POST['dueDate'] != "" &&
                $_POST['status'] != ""
            ) {
                // Create a TaskDAO object
                $taskDAO = new TaskDAO();
                
                // Check if the user is authorized to edit this task
                $task = $taskDAO->getTask($_POST['taskId']);
                if ($task && $task->getUserId() == $user->getId())  {
                    // Call the editTask method with the provided parameters
                    $result = $taskDAO->editTask(
                        $_POST['taskId'],
                        $_POST['taskName'],
                        $_POST['priority'],
                        $_POST['dueDate'],
                        $_POST['status']
                    );

                    if ($result > 0) {
                        header('Location: editTask.php?recordsUpdated=' . $result . '&taskId=' . $_POST['taskId']);
                    } else {
                        header('Location: editTask.php?taskId=' . $_POST['taskId']);
                    }
                } else {
                    // User is not authorized to edit this task
                    header('Location: index.php?unauthorized=true');
                }
            } else {
                header('Location: editTask.php?missingFields=true&taskId=' . $_POST['taskId']);
            }
        } else {
            header('Location: editTask.php?error=true&taskId=' . $_POST['taskId']);
        }
    }

    if ($_GET['action'] == "delete") {
        if (isset($_GET['taskId']) && is_numeric($_GET['taskId'])) {
            $taskDAO = new TaskDAO();

            // Check if the user is authorized to delete this task
            $task = $taskDAO->getTask($_GET['taskId']);
            if ($task && $task->getUserId() == $user->getId()) {
                $success = $taskDAO->deleteTask($_GET['taskId']);
                if ($success) {
                    header('Location: index.php?deleted=true');
                } else {
                    header('Location: index.php?deleted=false');
                }
            } else {
                // User is not authorized to delete this task
                header('Location: index.php?unauthorized=true');
            }
        }
    }
}

// If 'action' parameter is not set, redirect to the main page or wherever appropriate
header('Location: index.php');
exit();
?>
