<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeID = $conn->real_escape_string(trim($_POST['employeeID']));
    $password = $conn->real_escape_string(trim($_POST['password']));

    $sql = "SELECT * FROM tbl_employeeacc WHERE employee_id='$employeeID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['e_Password']) {
            session_start();
            $_SESSION['employee_id'] = $row['employee_id'];
            $_SESSION['e_Fname'] = $row['e_Fname'];
            $_SESSION['e_Lname'] = $row['e_Lname'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No account found with that Employee ID.');</script>";
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
