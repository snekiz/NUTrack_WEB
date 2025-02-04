<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php';

$rowsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

$status = isset($_GET['status']) ? $_GET['status'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Prepare the SQL query for fetching requests
$sql = "SELECT request_id, student_id, form_type, request_date, clearance, status FROM tbl_requests WHERE 1=1";

// Bind parameters based on user inputs
if ($status) {
    $sql .= " AND status = ?";
}
if ($search) {
    $sql .= " AND request_id = ?";
}

$sql .= " LIMIT $rowsPerPage OFFSET $offset";

$stmt = $database->prepare($sql);

if ($status && $search) {
    $stmt->bindValue(1, $status, PDO::PARAM_STR);
    $stmt->bindValue(2, $search, PDO::PARAM_STR);
} elseif ($status) {
    $stmt->bindValue(1, $status, PDO::PARAM_STR);
} elseif ($search) {
    $stmt->bindValue(1, $search, PDO::PARAM_STR);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results

// SQL query to count total rows for pagination
$sqlCount = "SELECT COUNT(*) as totalRows FROM tbl_requests WHERE 1=1";
if ($status) {
    $sqlCount .= " AND status = ?";
}
if ($search) {
    $sqlCount .= " AND request_id = ?";
}

$stmtCount = $database->prepare($sqlCount);
if ($status && $search) {
    $stmtCount->bindValue(1, $status, PDO::PARAM_STR);
    $stmtCount->bindValue(2, $search, PDO::PARAM_STR);
} elseif ($status) {
    $stmtCount->bindValue(1, $status, PDO::PARAM_STR);
} elseif ($search) {
    $stmtCount->bindValue(1, $search, PDO::PARAM_STR);
}
$stmtCount->execute();
$totalResult = $stmtCount->fetch(PDO::FETCH_ASSOC); // Correct usage of PDO method
$totalRows = $totalResult['totalRows'];
$totalPages = ceil($totalRows / $rowsPerPage);

// Handle request update
if (isset($_POST['save_changes'])) {
    $requestId = $_POST['request_id'];
    $clearance = $_POST['clearance'];
    $status = $_POST['status'];
    $currentStatus = isset($_POST['current_status']) ? $_POST['current_status'] : '';
    
    // Secure update query with parameter binding
    $sqlUpdate = "UPDATE tbl_requests SET clearance = ?, status = ? WHERE request_id = ?";
    $stmtUpdate = $database->prepare($sqlUpdate);
    $stmtUpdate->bindValue(1, $clearance, PDO::PARAM_STR);
    $stmtUpdate->bindValue(2, $status, PDO::PARAM_STR);
    $stmtUpdate->bindValue(3, $requestId, PDO::PARAM_INT);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Request updated successfully.'); window.location.href='dashboard.php?status=" . htmlspecialchars($currentStatus) . "';</script>";
    } else {
        echo "<script>alert('Error updating request.');</script>";
    }
}

// Handle request delete
if (isset($_POST['delete_request'])) {
    $requestId = $_POST['request_id'];

    $sqlCheck = "SELECT clearance FROM tbl_requests WHERE request_id = ?";
    $stmtCheck = $database->prepare($sqlCheck);
    $stmtCheck->bindValue(1, $requestId, PDO::PARAM_INT);
    $stmtCheck->execute();
    $clearance = $stmtCheck->fetchColumn(); // Fetch the clearance value

    if ($clearance !== 'NOT VALID') {
        echo "<script>alert('Request cannot be deleted. Set clearance to NOT VALID first.'); window.location.href='dashboard.php';</script>";
    } else {
        $sqlDelete = "DELETE FROM tbl_requests WHERE request_id = ?";
        $stmtDelete = $database->prepare($sqlDelete);
        $stmtDelete->bindValue(1, $requestId, PDO::PARAM_INT);

        if ($stmtDelete->execute()) {
            echo "<script>alert('Request deleted successfully.'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error deleting request.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Dashboard</title>
</head>
<body class="dashboard_body">
    <div class="top-bar">
        <div class="dashboard_logo">
            <img src="img/nulogo.png" class="dashboard_nulogo" alt="NU logo">
            <span class="logo-text">NUTrack</span>
        </div>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="index.php">Logout</a>
        </nav>
    </div>

    <div class="header">
        <h1>NUTrack Dashboard</h1>
        <p>Filter and manage requests by status.</p>
    </div>

    <div class="filter-buttons">
        <a href="dashboard.php?status=validating" 
           class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'validating') ? 'active' : ''; ?>">Validating</a>
        <a href="dashboard.php?status=processing" 
           class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'processing') ? 'active' : ''; ?>">Processing</a>
        <a href="dashboard.php?status=ready to pickup" 
           class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status'] == 'ready to pickup') ? 'active' : ''; ?>">Ready to Pickup</a>
    </div>

    <div class="search-container">
        <form method="GET" action="dashboard.php">
            <input type="text" name="search" placeholder="Search by Request ID" value="<?php echo htmlspecialchars($search); ?>" class="search-bar">
            <button type="submit" class="search-btn">Search</button>
        </form>
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
                if ($result) {
                    foreach ($result as $row) {
                        echo "<tr onclick=\"showModal('" . htmlspecialchars($row['request_id']) . "', '" . htmlspecialchars($row['student_id']) . "', '" . htmlspecialchars($row['form_type']) . "', '" . htmlspecialchars($row['request_date']) . "', '" . htmlspecialchars($row['clearance']) . "', '" . htmlspecialchars($row['status']) . "')\">
                                <td>" . htmlspecialchars($row['request_id']) . "</td>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['form_type']) . "</td>
                                <td>" . htmlspecialchars($row['request_date']) . "</td>
                                <td>" . htmlspecialchars($row['clearance']) . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No requests found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
            <?php endif; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="POST" action="">
                <input type="hidden" name="current_status" id="currentStatusInput">
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
                        <tr>
                            <td><input type="text" id="modalRequestId" name="request_id" readonly></td>
                            <td><input type="text" id="modalStudentId" readonly></td>
                            <td><input type="text" id="modalFormType" readonly></td>
                            <td><input type="text" id="modalRequestDate" readonly></td>
                            <td>
                                <select id="modalClearance" name="clearance">
                                    <option value="VALIDATED">VALIDATED</option>
                                    <option value="NOT VALID">NOT VALID</option>
                                </select>
                            </td>
                            <td>
                                <select id="modalStatus" name="status">
                                    <option value="VALIDATING">Validating</option>
                                    <option value="PROCESSING">Processing</option>
                                    <option value="READY TO PICKUP">Ready to Pickup</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" name="save_changes">Save Changes</button>
                <button type="submit" name="delete_request">Delete Request</button>
            </form>
        </div>
    </div>

    <script>
        function showModal(requestId, studentId, formType, requestDate, clearance, status) {
            document.getElementById("modalRequestId").value = requestId;
            document.getElementById("modalStudentId").value = studentId;
            document.getElementById("modalFormType").value = formType;
            document.getElementById("modalRequestDate").value = requestDate;
            document.getElementById("modalClearance").value = clearance;
            document.getElementById("modalStatus").value = status;
            document.getElementById("currentStatusInput").value = status;

            document.getElementById("myModal").style.display = "block";
        }

        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }
    </script>
</body>
</html>
