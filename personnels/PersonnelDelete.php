<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if PersonnelID is provided
if (isset($_GET['PersonnelID'])) {
    $personnel_id = $_GET['PersonnelID'];

    // Delete the record from the Personnel table
    $sql = "DELETE FROM Personnel WHERE PersonnelID = '$personnel_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: PersonnelIndex.php"); // Redirect to PersonnelIndex.php after deletion
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No Personnel ID specified.";
}
?>