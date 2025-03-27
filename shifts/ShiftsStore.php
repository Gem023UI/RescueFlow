<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if user is admin (role_id = 4)
if ($_SESSION['role'] != 4) {
    header("Location: ../dashboard/RescueFlowIndex.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $PersonnelID = $_POST['PersonnelID'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $shift_day = $_POST['shift_day'];
    $status = $_POST['status'];
    
    // Get the current user's personnelID from session
    $assigned_by = $_SESSION['user_id']; // Changed from $_POST to session

    // Validate inputs
    if (empty($PersonnelID) || empty($start_time) || empty($end_time) || empty($shift_day)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: ShiftsCreate.php");
        exit();
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO shift_schedule (PersonnelID, start_time, end_time, shift_day, status, assigned_by) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $PersonnelID, $start_time, $end_time, $shift_day, $status, $assigned_by);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Shift schedule added successfully";
    } else {
        $_SESSION['error'] = "Error adding shift schedule: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: ShiftsIndex.php");
    exit();
} else {
    header("Location: ShiftsCreate.php");
    exit();
}