<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if user is admin (role_id = 4)
if ($_SESSION['role'] != 4) {
    header("Location: ../dashboard/RescueFlowIndex.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $schedule_id = $_POST['schedule_id'];
    $PersonnelID = $_POST['PersonnelID'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $shift_day = $_POST['shift_day'];
    $status = $_POST['status'];
    
    // Get the current user's personnelID from session for tracking who made the change
    $updated_by = $_SESSION['user_id'];

    // Validate inputs
    if (empty($PersonnelID) || empty($start_time) || empty($end_time) || empty($shift_day)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: ShiftsEdit.php?schedule_id=$schedule_id");
        exit();
    }

    // Update the shift schedule
    $stmt = $conn->prepare("UPDATE shift_schedule SET 
                          PersonnelID = ?, 
                          start_time = ?, 
                          end_time = ?, 
                          shift_day = ?, 
                          status = ?,
                          assigned_by = ?,
                          assigned_time = CURRENT_TIMESTAMP
                          WHERE schedule_id = ?");
    
    $stmt->bind_param("issssii", $PersonnelID, $start_time, $end_time, $shift_day, $status, $updated_by, $schedule_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Shift schedule updated successfully";
    } else {
        $_SESSION['error'] = "Error updating shift schedule: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ShiftsIndex.php");
    exit();
} else {
    header("Location: ShiftsIndex.php");
    exit();
}