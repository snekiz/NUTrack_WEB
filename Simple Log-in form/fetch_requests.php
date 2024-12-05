<?php
include 'db_connect.php'; 

$sql = "SELECT request_id, student_id, form_type, request_date, clearance FROM tbl_requests";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['request_id']}</td>
                <td>{$row['student_id']}</td>
                <td>{$row['form_type']}</td>
                <td>{$row['request_date']}</td>
                <td>{$row['clearance']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No requests found</td></tr>";
}

$conn->close();
?>
