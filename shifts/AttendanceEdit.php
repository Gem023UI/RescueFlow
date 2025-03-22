<?php
session_start();
include('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['attendance_id']) && isset($_GET['time_out'])) {
    $attendanceId = intval($_GET['attendance_id']);
    $newTimeOut = $_GET['time_out'];

    // Update the Time Out column in the attendance table
    $query = "UPDATE attendance SET time_out = ? WHERE attendance_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newTimeOut, $attendanceId);

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