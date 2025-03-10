<?php
include('../includes/config.php');

// Check for connection error
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $positionId = $_POST['PositionID'];
  $firstName = $_POST['FirstName'];
  $lastName = $_POST['LastName'];
  $designated = $_POST['Designated'];

  // Handle file upload
  $picture = '';
  if (isset($_FILES['Picture']) && $_FILES['Picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'images/';
    $uploadFile = $uploadDir . basename($_FILES['Picture']['name']);
    if (move_uploaded_file($_FILES['Picture']['tmp_name'], $uploadFile)) {
      $picture = $uploadFile;
    } else {
      echo "Error uploading file.";
      exit;
    }
  } else {
    echo "No file uploaded or upload error.";
    exit;
  }

  // Insert into database
  $stmt = $conn->prepare("INSERT INTO personnel (PositionID, FirstName, LastName, Designated, Picture) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $positionId, $firstName, $lastName, $designated, $picture);

  if ($stmt->execute()) {
    echo "Personnel added successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
  $conn->close();
}
?>
