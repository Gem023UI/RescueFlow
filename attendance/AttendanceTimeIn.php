<?php
session_start(); // Start the session

// Include database connection
include('../includes/config.php');

// Check if the request contains PersonnelID
if (!isset($_POST['PersonnelID'])) {
    echo json_encode(['error' => 'PersonnelID not provided']);
    exit;
}

// Extract PersonnelID from the POST request
$personnelID = $_POST['PersonnelID'];

// Insert into the attendance table
$query = "INSERT INTO attendance (personnel_id, timestamp, shift_id) VALUES (?, NOW(), 2)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $personnelID);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Attendance recorded successfully']);
} else {
    echo json_encode(['error' => 'Error recording attendance']);
}

$stmt->close();
$conn->close();
?>