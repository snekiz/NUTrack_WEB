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
            <img src="img/nulogo.png" class="nulogo" alt="nulogo">
            <span class="logo-text">NUTrack</span>
        </div>
        <nav class="nav-links">
            <a href="Dashboard.html">Dashboard</a>
            <a href="#MyAccount">My Account</a>
            <a href="Login.html">Logout</a>
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
                </tr>
            </thead>
            <tbody>
                <?php include 'fetch_requests.php'; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
