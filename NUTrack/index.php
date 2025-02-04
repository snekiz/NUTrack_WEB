<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = trim($_POST['employeeID']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM tbl_employeeacc WHERE employee_id = :employeeID";
    $stmt = $database->prepare($sql);
    $stmt->execute(['employeeID' => $employeeID]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['e_Password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php');
        exit;
    } else {
        echo "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body class="login_signup_body">
    <div class="login-box">
        <div class="logo">
            <img src="img/nulogo.png" class="nulogo" alt="NU logo">
        </div>
        <div class="login-header">
            <header>Login</header>
        </div>
        <form action="index.php" method="POST">
            <div class="input-box">
                <input type="number" name="employeeID" class="input-field" placeholder="Employee ID" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
            </div>  
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check">
                    <label for="check">Remember me</label>
                </section>
                <section>
                    <a href="#">Forgot password</a>
                </section>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn">Sign In</button>
            </div>
        </form>
        <div class="sign-up-link">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
