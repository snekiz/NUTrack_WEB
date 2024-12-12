<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $conn->real_escape_string(trim($_POST['firstName']));
    $lastName = $conn->real_escape_string(trim($_POST['lastName']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $employeeID = $conn->real_escape_string(trim($_POST['employeeID']));
    $password = $conn->real_escape_string(trim($_POST['password']));

    $sql = "INSERT INTO tbl_employeeacc (employee_id, e_FirstName, e_LastName, e_Email, e_Password) 
            VALUES ('$employeeID', '$firstName', '$lastName', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?signup=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
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
            <img src="img/nulogo.png" class="nulogo" alt="NU logo">
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
                <input type="email" name="email" class="input-field" placeholder="Email" autocomplete="off" required>
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
            <p>Already have an account? <a href="index.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
