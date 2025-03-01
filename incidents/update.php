<?php
ob_start();
require '../includes/config.php';
include('../includes/restrict_admin.php');
include('../includes/check_admin.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $incident_id = intval($_POST['incident_id']);
    $incident_type = $_POST['incident_type'];
    $barangay_id = intval($_POST['barangay_id']);
    $reported_by = $_POST['reported_by'] ?? null;
    $severity_id = intval($_POST['severity_id']);
    $cause = $_POST['cause'];  // Capture the cause field
    $address = $_POST['address']; // Capture the address field

    // Fetch current attachments from the database (if any)
    $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $existing_attachments = $row ? $row['attachments'] : '';  
    $stmt->close();

     // Update query with barangay_id instead of location
     $sql = "UPDATE incidents SET
     incident_type = ?,
     barangay_id = ?, 
     reported_by = ?,
     severity_id = ?,
     cause = ?,
     address = ?
     WHERE incident_id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisissi", $incident_type, $barangay_id, $reported_by, $severity_id, $cause, $address, $incident_id);

    // Execute the update
    if ($stmt->execute()) {
        header("Location: index.php?id=" . $incident_id);
        exit();
    } else {
        echo "Error updating the incident.";
    }
}
?>
