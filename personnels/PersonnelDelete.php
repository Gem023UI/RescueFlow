<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if PersonnelID is provided
if (isset($_GET['PersonnelID'])) {
    $personnel_id = $_GET['PersonnelID'];

    // Start a transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Step 1: Delete related records in the attendance table
        $sql_delete_attendance = "DELETE FROM attendance WHERE personnel_id = '$personnel_id'";
        if ($conn->query($sql_delete_attendance) !== TRUE) {
            throw new Exception("Error deleting related attendance records: " . $conn->error);
        }

        // Step 2: Delete the record from the Personnel table
        $sql_delete_personnel = "DELETE FROM Personnel WHERE PersonnelID = '$personnel_id'";
        if ($conn->query($sql_delete_personnel) !== TRUE) {
            throw new Exception("Error deleting personnel record: " . $conn->error);
        }

        // Commit the transaction if both queries succeed
        $conn->commit();

        // Redirect to PersonnelIndex.php after successful deletion
        header("Location: PersonnelIndex.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if any query fails
        $conn->rollback();
        echo $e->getMessage(); // Display the error message
    }
} else {
    echo "No Personnel ID specified.";
}
?>