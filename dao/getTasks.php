<?php

require_once('AbstractDAO.php');

class GetTasks extends AbstractDAO {

    public function getTasks() {
        $query = 'SELECT * FROM tasks';
        $result = $this->mysqli->query($query);
        $tasks = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
            $result->free();
            return $tasks;
        }

        $result->free();
        return false;
    }
}

?>
