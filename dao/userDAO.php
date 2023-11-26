<?php
require_once('abstractDAO.php');
require_once('./model/user.php');

error_reporting(0);

class UserDAO extends AbstractDAO {

    function __construct() {
        try {
            parent::__construct();
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

    public function getUserByEmail($email) {
        $query = 'SELECT * FROM users WHERE email = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $temp = $result->fetch_assoc();
            $user = new User($temp['id'], $temp['email'], $temp['firstName'], $temp['lastName']);
            $result->free();
            return $user;
        }

        $result->free();
        return false;
    }

    // You can add more methods for user-related operations here, such as addUser, updateUser, etc.

    public function __destruct() {
        $this->mysqli->close();
    }
}
?>
