<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['attendance_id'])) {
    $attendanceId = intval($_GET['attendance_id']);

    // Delete the row from the attendance table
    $query = "DELETE FROM attendance WHERE attendance_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $attendanceId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>