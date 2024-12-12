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
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
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
                        <td id="modalRequestId"></td>
                        <td id="modalStudentId"></td>
                        <td id="modalFormType"></td>
                        <td id="modalRequestDate"></td>
                        <td>
                            <select id="modalClearance">
                                <option value="VALIDATED">VALIDATED</option>
                                <option value="NOT VALID">NOT VALID</option>
                            </select>
                        </td>
                        <td>
                            <select id="modalStatus">
                                <option value="VALIDATING">VALIDATING</option>
                                <option value="PROCESSING">PROCESSING</option>
                                <option value="READY TO PICKUP">READY TO PICKUP</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="modal-actions">
                <button onclick="saveChanges()">Save</button>
                <button onclick="deleteRow()">Delete</button>
            </div>
        </div>
    </div>

    <script>
    function showModal(requestId, studentId, formType, requestDate, clearance, status) {
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        document.getElementById("modalRequestId").textContent = requestId;
        document.getElementById("modalStudentId").textContent = studentId;
        document.getElementById("modalFormType").textContent = formType;
        document.getElementById("modalRequestDate").textContent = requestDate;
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

    function saveChanges() {
        alert('Save functionality to be implemented');
    }

    function deleteRow() {
        alert('Delete functionality to be implemented');
    }
    </script>
</body>
</html>
