<?php
include('../includes/config.php');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT OfficeID, OfficeName FROM office";
$result = $conn->query($sql);

$offices = [];
while ($row = $result->fetch_assoc()) {
  $offices[] = ["id" => $row['OfficeID'], "name" => $row['OfficeName']];
}

echo json_encode($offices);
$conn->close();
?>
