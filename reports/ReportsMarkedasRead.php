<?php
session_start();
include('../includes/config.php');

if (!isset($conn)) {
    die("Database connection failed.");
}

if (isset($_GET['mark_as_resolved'])) {
    $emergency_id = intval($_GET['mark_as_resolved']); // Get the emergency_details ID from the URL
    $update_sql = "UPDATE emergency_details SET status = 3 WHERE id = $emergency_id"; // Set status to 3 (Resolved)

    if ($conn->query($update_sql)) {
        $_SESSION['success_message'] = "Emergency record marked as resolved successfully!";
    } else {
        $_SESSION['error_message'] = "Error marking emergency record as resolved: " . $conn->error;
    }
}

// Redirect back to the reports page
header("Location: ReportsIndex.php");
exit();
?>