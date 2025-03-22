<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    // Update the personnel's shift status to "On Duty" (ShiftID = 2)
    $updateQuery = "UPDATE personnel SET ShiftID = 2 WHERE PersonnelID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $personnelID);
    $updateStmt->execute();
    $updateStmt->close();

    echo json_encode(['success' => 'Attendance recorded successfully']);
} else {
    echo json_encode(['error' => 'Error recording attendance']);
}

$stmt->close();
$conn->close();
?>