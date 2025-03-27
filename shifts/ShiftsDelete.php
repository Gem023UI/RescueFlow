<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if user is admin (role_id = 4)
if ($_SESSION['role'] != 4) {
    header("Location: ../dashboard/RescueFlowIndex.php");
    exit();
}

if (isset($_GET['schedule_id']) && is_numeric($_GET['schedule_id'])) {
    $schedule_id = $_GET['schedule_id'];

    // Verify the shift schedule exists
    $check_stmt = $conn->prepare("SELECT * FROM shift_schedule WHERE schedule_id = ?");
    $check_stmt->bind_param("i", $schedule_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Shift schedule not found";
        header("Location: ShiftsIndex.php");
        exit();
    }

    // Delete the specific shift schedule
    $delete_stmt = $conn->prepare("DELETE FROM shift_schedule WHERE schedule_id = ?");
    $delete_stmt->bind_param("i", $schedule_id);
    
    if ($delete_stmt->execute()) {
        $_SESSION['success'] = "Shift schedule deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting shift schedule: " . $conn->error;
    }

    $delete_stmt->close();
    $check_stmt->close();
} else {
    $_SESSION['error'] = "Invalid shift schedule ID";
}

$conn->close();
header("Location: ShiftsIndex.php");
exit();
?>