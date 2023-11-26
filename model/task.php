<?php
class Task {
    private $taskId;
    private $taskName;
    private $priority;
    private $dueDate;
    private $status;
    private $user_id;

    function __construct($taskId, $taskName, $priority, $dueDate, $status, $user_id) {
        $this->setTaskId($taskId);
        $this->setTaskName($taskName);
        $this->setPriority($priority);
        $this->setDueDate($dueDate);
        $this->setStatus($status);
        $this->setUserId($user_id);
    }

    public function getTaskId() {
        return $this->taskId;
    }

    public function setTaskId($taskId) {
        $this->taskId = $taskId;
    }

    public function getTaskName() {
        return $this->taskName;
    }

    public function setTaskName($taskName) {
        $this->taskName = $taskName;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getDueDate() {
        return $this->dueDate;
    }

    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }
}
?>
