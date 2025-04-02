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
        // Step 1: Update the personnelstatus_id to 2 (Not Active) instead of deleting
        $sql_update_personnel = "UPDATE Personnel SET personnelstatus_id = 2 WHERE PersonnelID = '$personnel_id'";
        if ($conn->query($sql_update_personnel) !== TRUE) {
            throw new Exception("Error updating personnel status: " . $conn->error);
        }

        // Commit the transaction if the query succeeds
        $conn->commit();

        // Redirect to PersonnelIndex.php after successful update
        header("Location: PersonnelIndex.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if the query fails
        $conn->rollback();
        echo $e->getMessage(); // Display the error message
    }
} else {
    echo "No Personnel ID specified.";
}
?>