<?php
require_once('./dao/userDAO.php');

session_start();

if (isset($_POST['btnLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $userDAO = new UserDAO();
        $user = $userDAO->getUserByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Authentication successful, redirect to the dashboard or home page
            $_SESSION['userId'] = $user->getPassword();
            header("Location: dashboard.php");
            exit();
        } else {
            $loginError = "Invalid email or password. Please try again.";
        }
    } catch (Exception $e) {
        $loginError = "Error during login: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>Login</h2>
    <?php
    if (isset($loginError)) {
        echo '<p style="color: red;">' . $loginError . '</p>';
    }
    ?>
    <form name="loginForm" method="post" action="login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" name="btnLogin" id="btnLogin" value="Login">
    </form>
</body>
</html>
