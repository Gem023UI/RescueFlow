<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Adjust this path if necessary

session_start();
include('../includes/config.php');

// Handle Dispatch Submission with Emergency Report
if (isset($_POST["submit_emergency_info"])) {
    // Get emergency report details
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $why = mysqli_real_escape_string($conn, $_POST['why']);
    $caller_name = mysqli_real_escape_string($conn, $_POST['caller_name']);
    $caller_phone = mysqli_real_escape_string($conn, $_POST['caller_phone']);
    $dispatch_id = $_POST['dispatch_id'];

    // Insert emergency details
    $sql = "INSERT INTO emergency_details (dispatch_id, what, `where`, `why`, caller_name, caller_phone, status) 
            VALUES ('$dispatch_id', '$what', '$where', '$why', '$caller_name', '$caller_phone', 1)";

    if (mysqli_query($conn, $sql)) {
        // Fetch on-duty personnel
        $onDutyQuery = "SELECT p.Email, p.FirstName, p.LastName 
                        FROM personnel p 
                        JOIN shift_schedule ss ON p.PersonnelID = ss.PersonnelID
                        WHERE ss.status = 'On Duty'";
        $onDutyResult = mysqli_query($conn, $onDutyQuery);

        // Email details with emergency report information
        $subject = "🚨 EMERGENCY ALERT: Dispatch to " . htmlspecialchars($where);
        $message = "
EMERGENCY DETAILS
=================
Location: " . htmlspecialchars($where) . "
Type: " . htmlspecialchars($what) . "
Situation: " . htmlspecialchars($why) . "

CALLER INFORMATION
=================
Name: " . htmlspecialchars($caller_name) . "
Phone: " . htmlspecialchars($caller_phone) . "

RESPONSE TEAM
=============
Dispatched Unit: All Firetruck and Emergency Vehicles
Status: In Progress

ACTION REQUIRED
===============
Please proceed to the location immediately and take necessary actions.
Report your status updates through the RescueFlow system.
";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'flintaxl.celetaria@gmail.com';
            $mail->Password = 'whif dedq ytly ryfo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('flintaxl.celetaria@gmail.com', 'RescueFlow Dispatch');
            $mail->isHTML(false);  // Plain text email

            // Send email to each on-duty personnel
            while ($row = mysqli_fetch_assoc($onDutyResult)) {
                $mail->addAddress($row['Email'], $row['FirstName'] . ' ' . $row['LastName']);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                $mail->clearAddresses();  // Clear addresses for the next email
            }
            
            $_SESSION['success_message'] = "Emergency reported and personnel notified successfully!";
            header("Location: ../user/FrontPage.html");
            exit();
        } catch (Exception $e) {
            echo "<p style='color: red;'>Mailer Error: " . $mail->ErrorInfo . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Error saving emergency details: " . mysqli_error($conn) . "</p>";
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
            <h2>EMERGENCY DETAILS REPORT</h2>
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