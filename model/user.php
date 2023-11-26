<?php

class User {
    private $id;
    private $email;
    private $firstName;
    private $lastName;

    public function __construct($id, $email, $firstName, $lastName) {
        $this->setId($id);
        $this->setEmail($email);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function getCurrentUser() {
        // Add your authentication logic here.
        // This is just a placeholder; you might use session, cookies, or any other method.
        
        // For example, if using session:
        session_start();
    
        if (isset($_SESSION['user_id'])) {
            // Assuming you have a method to fetch user details based on the user ID
            $user = getUserById($_SESSION['user_id']);
    
            if ($user) {
                return $user;
            }
        }
    
        // If not authenticated, return null or redirect to the login page.
        return null;
    }
}

?>
