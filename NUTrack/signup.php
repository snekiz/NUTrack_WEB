<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $employeeID = $_POST['employeeID'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, email, employee_id, password) 
            VALUES ('$firstName', '$lastName', '$email', '$employeeID', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Signup</title>
</head>
<body>
    <div class="signup-box">
        <div class="logo">
            <img src="img/nulogo.png" class="nulogo" alt="nulogo">
        </div>
        <div class="signup-header">
            <header>Signup</header>
        </div>
        <form action="signup.php" method="POST">
            <div class="input-box">
                <input type="text" name="firstName" class="input-field" placeholder="First name" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" name="lastName" class="input-field" placeholder="Last name" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="number" name="employeeID" class="input-field" placeholder="EmployeeID" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn">Sign Up</button>
            </div>
        </form>        
        <div class="sign-up-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    <script>
        document.getElementById('submit').addEventListener('click', function () {
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
