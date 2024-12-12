<?php
include 'db_connect.php';

$rowsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage; 

$sql = "SELECT COUNT(*) as totalRows FROM tbl_requests";
$totalResult = $conn->query($sql);
$totalRows = $totalResult->fetch_assoc()['totalRows'];
$totalPages = ceil($totalRows / $rowsPerPage);

$sql = "SELECT request_id, student_id, form_type, request_date, clearance, status 
        FROM tbl_requests 
        LIMIT $rowsPerPage OFFSET $offset";
$result = $conn->query($sql);

if (isset($_POST['save_changes'])) {
    $requestId = $_POST['request_id'];
    $clearance = $_POST['clearance'];
    $status = $_POST['status'];

    $sql = "UPDATE tbl_requests SET clearance = ?, status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $clearance, $status, $requestId);

    if ($stmt->execute()) {
        echo "<script>alert('Request updated successfully.'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating request.');</script>";
    }
}

if (isset($_POST['delete_request'])) {
    $requestId = $_POST['request_id'];

    $sqlCheck = "SELECT clearance FROM tbl_requests WHERE request_id = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $requestId);
    $stmtCheck->execute();
    $stmtCheck->bind_result($clearance);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($clearance !== 'NOT VALID') {
        echo "<script>alert('Request cannot be deleted. Set clearance to NOT VALID first.'); window.location.href='dashboard.php';</script>";
    } else {
        $sqlDelete = "DELETE FROM tbl_requests WHERE request_id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $requestId);

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
            <a href="dashboard.php">Dashboard</a>
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
                    echo "<tr onclick=\"showModal('{$row['request_id']}', '{$row['student_id']}', '{$row['form_type']}', '{$row['request_date']}', '{$row['clearance']}', '{$row['status']}')\">
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
            ?>
        </tbody>
    </table>
</div>

<div class="pagination-container">
    <span>Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="POST" action="">
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
                                    <option value="VALIDATING">VALIDATING</option>
                                    <option value="PROCESSING">PROCESSING</option>
                                    <option value="READY TO PICKUP">READY TO PICKUP</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="modal-actions">
                    <button type="submit" name="save_changes">Save</button>
                    <button type="submit" name="delete_request">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showModal(requestId, studentId, formType, requestDate, clearance, status) {
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        document.getElementById("modalRequestId").value = requestId;
        document.getElementById("modalStudentId").value = studentId;
        document.getElementById("modalFormType").value = formType;
        document.getElementById("modalRequestDate").value = requestDate;
        document.getElementById("modalClearance").value = clearance;
        document.getElementById("modalStatus").value = status;

        modal.style.display = "block";

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    </script>
</body>
</html>
