<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['PersonnelID'])) {  // Ensure this matches login session
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Fetch the PersonnelID from the session
$personnelID = $_SESSION['PersonnelID'];

include('../includes/config.php');

// Check if the user already timed in today
$today = date("Y-m-d"); // Get today's date in YYYY-MM-DD format
$checkQuery = "SELECT * FROM attendance WHERE personnel_id = ? AND DATE(timestamp) = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("is", $personnelID, $today);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    // User already timed in today
    echo "You already TIME IN. Wait for the Admin response for the TIME OUT availability.";
    exit;
}

// If no record exists for today, proceed with time-in
$query = "INSERT INTO attendance (personnel_id, timestamp, shift_id) VALUES (?, NOW(), 2)";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $personnelID);

if ($stmt->execute()) {
    // Update shift status
    $updateQuery = "UPDATE personnel SET ShiftID = 2 WHERE PersonnelID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("i", $personnelID);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect to a customizable directory after successful time-in
    $redirectPath = "../shifts/ShiftsIndex.php"; // Customize this path
    header("Location: " . $redirectPath);
    exit();
} else {
    // Handle error
    echo "Error recording attendance.";
}



$stmt->close();
$checkStmt->close();
$conn->close();
?>