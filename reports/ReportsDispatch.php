<?php
session_start();
include('../includes/config.php');

if (!isset($conn)) {
    die("Database connection failed.");
}

if (isset($_GET['emergency_id'])) {
    $emergency_id = intval($_GET['emergency_id']); // Get the emergency_details ID from the URL

    // Update the status of the emergency record to "In progress" (status_id = 2)
    $update_sql = "UPDATE emergency_details SET status = 2 WHERE id = $emergency_id"; 

    if ($conn->query($update_sql)) {
        $_SESSION['success_message'] = "Emergency record marked as in progress!";
    } else {
        $_SESSION['error_message'] = "Error marking emergency record as in progress: " . $conn->error;
    }
}

// Redirect back to the dispatch page
header("Location: ../dispatch/DispatchIndex.php");
exit();
?>