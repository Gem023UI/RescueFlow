<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['PersonnelID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Fetch the PersonnelID from the session
$personnelID = $_SESSION['PersonnelID'];

// Return the PersonnelID as JSON
echo json_encode(['PersonnelID' => $personnelID]);
?>