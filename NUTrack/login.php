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
