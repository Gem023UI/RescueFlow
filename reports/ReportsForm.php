<?php
session_start();
include('../includes/config.php');

// Handle emergency data entry
if (isset($_POST['submit_emergency_info'])) {
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $why = mysqli_real_escape_string($conn, $_POST['why']);
    $caller_name = mysqli_real_escape_string($conn, $_POST['caller_name']);
    $caller_phone = mysqli_real_escape_string($conn, $_POST['caller_phone']);
    $dispatch_id = $_POST['dispatch_id']; 

    // Include status = 1 (Pending) in the INSERT query
    $sql = "INSERT INTO emergency_details (dispatch_id, what, `where`, `why`, caller_name, caller_phone, status) 
            VALUES ('$dispatch_id', '$what', '$where', '$why', '$caller_name', '$caller_phone', 1)";

    if (mysqli_query($conn, $sql)) {
        echo "Emergency information saved successfully!</p>";
    } else {
        echo "Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="ReportsForm.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REPORT</title>
</head>
<body>
    <!-- Emergency Details Form -->
    <div class="report-container">
        <div class="report-header">
            <h2>EMERGENCY DETAILS INPUT</h2>
        </div>
        <div class="report-form">
            <form method="POST">
                <input type="hidden" name="dispatch_id" value="1">
                <div class="report-details">
                    <label for="what">What happened?</label>
                    <textarea name="what" placeholder="Describe the emergency" required></textarea>
                    <label for="where">Where did it happen?</label>
                    <textarea name="where" placeholder="Provide the location" required></textarea>
                </div>
                <div class="report-details">
                    <label for="why">Why is this an emergency?</label>
                    <textarea name="why" placeholder="Explain the situation" required></textarea>
                </div>
                <div class="report-details">
                    <label for="caller_name">Caller Name</label>
                    <input type="text" class="form-control" name="caller_name" placeholder="Enter caller's name" required>
                    <label for="caller_phone">Caller Phone Number</label>
                    <input type="text" class="form-control" name="caller_phone" placeholder="Enter caller's phone number" required>
                </div>
                <button type="submit" name="submit_emergency_info" class="btn btn-warning w-100">REPORT</button>
            </form>
        </div>
    </div>
</body>
</html>