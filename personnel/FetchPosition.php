<?php
include('../includes/config.php');
// Check for connection error
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if OfficeID is provided
if (isset($_GET['OfficeID'])) {
  $officeId = $_GET['OfficeID'];

  // Prepare SQL query to fetch positions based on OfficeID
  $stmt = $conn->prepare("SELECT PositionID, PositionName FROM position WHERE OfficeID = ?");
  $stmt->bind_param("i", $officeId);
  $stmt->execute();
  $result = $stmt->get_result();

  // Fetch results and send as JSON response
  $positions = [];
  while ($row = $result->fetch_assoc()) {
    $positions[] = ["id" => $row['PositionID'], "name" => $row['PositionName']];
  }

  echo json_encode($positions); // Send JSON response
  $stmt->close();
}

// Close database connection
$conn->close();
?>
