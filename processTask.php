<?php
require_once('./dao/taskDAO.php');

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
                header('Location: editTask.php?missingFields=true&taskId=' . $_POST['taskId']);
            }
        } else {
            header('Location: editTask.php?error=true&taskId=' . $_POST['taskId']);
        }
    }

    if ($_GET['action'] == "delete") {
        if (isset($_GET['taskId']) && is_numeric($_GET['taskId'])) {
            $taskDAO = new TaskDAO();
            $success = $taskDAO->deleteTask($_GET['taskId']);
            if ($success) {
                header('Location: index.php?deleted=true');
            } else {
                header('Location: index.php?deleted=false');
            }
        }
    }
}

// If 'action' parameter is not set, redirect to the main page or wherever appropriate
header('Location: index.php');
exit();
?>
