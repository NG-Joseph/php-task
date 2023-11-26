<?php
require_once('abstractDAO.php');
require_once('./model/task.php');

error_reporting(0);

/**
 * Description of employeeDAO
 *
 * @author Beulah Nwokotubo
 */
class TaskDAO extends AbstractDAO {

    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }

    public function getTasks() {
        $result = $this->mysqli->query('SELECT * FROM tasks');
        $tasks = Array();

        if ($result->num_rows >= 1) {
            while ($row = $result->fetch_assoc()) {
                $task = new Task($row['taskId'], $row['taskName'], $row['priority'], $row['dueDate'], $row['status']);
                $tasks[] = $task;
            }
            $result->free();
            return $tasks;
        }

        $result->free();
        return false;
    }

    public function getTask($taskId) {
        $query = 'SELECT * FROM tasks WHERE taskId = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $taskId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $task = new Task($temp['taskId'], $temp['taskName'], $temp['priority'], $temp['dueDate'], $temp['status']);
            $result->free();
            return $task;
        }

        $result->free();
        return false;
    }

    public function addTask($task) {
        if (!is_numeric($task->getTaskId())) {
            return 'TaskId must be a number.';
        }

        if (!$this->mysqli->connect_errno) {
            $query = 'INSERT INTO tasks VALUES (?,?,?,?,?)';
            $stmt = $this->mysqli->prepare($query);

            $stmt->bind_param('issss',
                $task->getTaskId(),
                $task->getTaskName(),
                $task->getPriority(),
                $task->getDueDate(),
                $task->getStatus()
            );

            $stmt->execute();

            if ($stmt->error) {
                return $stmt->error;
            } else {
                return $task->getTaskName() . ' added successfully.';
            }
        } else {
            return 'Could not connect to Database.';
        }
    }

    public function deleteTask($taskId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM tasks WHERE taskId = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $taskId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function editTask($taskId, $taskName, $priority, $dueDate, $status){
        if(!$this->mysqli->connect_errno){
            $query = 'UPDATE tasks SET taskName = ?, priority = ?, dueDate = ?, status = ?  WHERE taskId = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('ssssi', $taskName, $priority, $dueDate, $status, $taskId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return $stmt->affected_rows;
            }
        } else {
            return false;
        }
    }

    // Add methods for updating and deleting tasks as needed
}
?>
