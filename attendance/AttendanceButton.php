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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        echo json_encode(['success' => 'Attendance recorded successfully']);
    } else {
        echo json_encode(['error' => 'Error recording attendance']);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BFP NCR Taguig City</title>
  <link rel="stylesheet" href="">
  <style>
    .floating-btn {
        width: 90px;
        height: 90px;
        background-color: black;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        position: fixed;
        right: 30px;
        bottom: 140px;
        border: none;
        cursor: pointer;
        z-index: 9999; /* Ensures it's on top of everything */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); /* Optional: Adds shadow for better visibility */
    }
  </style>
</head>
<body>
    <!-- Floating Button -->
    <form method="POST" action="">
        <button type="submit" class="floating-btn" name="timeInBtn">
            <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#FFFFFF">
                <path d="M440-120q-75 0-140.5-28T185-225q-49-49-77-114.5T80-480q0-75 28-140.5T185-735q49-49 114.5-77T440-840q21 0 40.5 2.5T520-830v82q-20-6-39.5-9t-40.5-3q-118 0-199 81t-81 199q0 118 81 199t199 81q118 0 199-81t81-199q0-11-1-20t-3-20h82q2 11 2 20v20q0 75-28 140.5T695-225q-49 49-114.5 77T440-120Zm112-192L400-464v-216h80v184l128 128-56 56Zm168-288v-120H600v-80h120v-120h80v120h120v80H800v120h-80Z"/>
            </svg>
        </button>
    </form>
</body>
</html>