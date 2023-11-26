<?php
require_once('./dao/userDAO.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form submitted, process registration
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];

    try {
        $userDAO = new UserDAO();

        // Check if the email is already registered
        $existingUser = $userDAO->getUserByEmail($email);
        if ($existingUser) {
            $registrationError = "Email already exists. Please use a different email.";
        } else {
            // Hash the password before storing it in the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create a new user object
            $newUser = new User(null, $email, $firstName, $lastName, $hashedPassword);

            // Add the new user to the database
            $userDAO->addUser($newUser);

            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        }
    } catch (Exception $e) {
        $registrationError = "Error during registration: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <h2>Register</h2>
    <?php
    if (isset($registrationError)) {
        echo '<p style="color: red;">' . $registrationError . '</p>';
    }
    ?>
    <form name="registerForm" method="post" action="register.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="firstName">First Name:</label>
        <input type="text" name="firstName" id="firstName" required>

        <label for="lastName">Last Name:</label>
        <input type="text" name="lastName" id="lastName" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" name="btnRegister" id="btnRegister" value="Register">
    </form>
</body>
</html>
