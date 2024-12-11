<?php
include 'db_connect.php'; 

$sql = "SELECT request_id, student_id, form_type, request_date, clearance, status FROM tbl_requests";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="top-bar">
        <div class="logo">
            <img src="img/nulogo.png" class="nulogo" alt="NU logo">
            <span class="logo-text">NUTrack</span>
        </div>
        <nav class="nav-links">
            <a href="Dashboard.php">Dashboard</a>
            <a href="index.php">Logout</a>
        </nav>
    </div>
    
    <div class="header">
        <h1>NUTrack Dashboard</h1>
        <p>This is where the requests are displayed.</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>RequestID</th>
                    <th>StudentID</th>
                    <th>Form Type</th>
                    <th>Request Date</th>
                    <th>Clearance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['request_id']}</td>
                                <td>{$row['student_id']}</td>
                                <td>{$row['form_type']}</td>
                                <td>{$row['request_date']}</td>
                                <td>{$row['clearance']}</td>
                                <td>{$row['status']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No requests found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
