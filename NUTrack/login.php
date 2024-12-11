<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = $_POST['employeeID'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE employee_id='$employeeID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login successful!";
            header("Location: Dashboard.html");
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with that Employee ID.";
    }

    $conn->close();
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
<body>
    <div class="login-box">
        <div class="logo">
            <img src="img/nulogo.png" class="nulogo" alt="nulogo">
        </div>
        <div class="login-header">
            <header>Login</header>
        </div>
        <form action="login.php" method="POST">
            <div class="input-box">
                <input type="number" name="employeeID" class="input-field" placeholder="EmployeeID" autocomplete="off" required>
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
            <button class="submit-btn" id="submit"></button>
            <label for="submit">Sign In</label>
        </div>
    </form>
        <div class="sign-up-link">
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </div>
    </div>
    <script>
        document.getElementById('submit').addEventListener('click', function () {
            window.location.href = 'dashboard.php';
        });
    </script>
</body>
</html>