<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Adjust this path if necessary

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
    echo "<script>alert('You already TIME IN. Wait for the Admin response for the TIME OUT availability.'); window.location.href='/RESCUEFLOW(1)/shifts/ShiftsIndex.php';</script>";
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

    // Fetch the personnel's details for the email
    $personnelQuery = "SELECT p.FirstName, p.LastName, r.rank_name 
                       FROM personnel p 
                       JOIN ranks r ON p.RankID = r.rank_id 
                       WHERE p.PersonnelID = ?";
    $personnelStmt = $conn->prepare($personnelQuery);
    $personnelStmt->bind_param("i", $personnelID);
    $personnelStmt->execute();
    $personnelResult = $personnelStmt->get_result();
    $personnelData = $personnelResult->fetch_assoc();
    $personnelStmt->close();

    // Prepare the email content
    $rank = $personnelData['rank_name'];
    $firstName = $personnelData['FirstName'];
    $lastName = $personnelData['LastName'];
    $emailSubject = "Time In Notification";
    $emailBody = "$rank $firstName $lastName is now on duty. Set the according Time Out once personnel's presence is verified. Refer to 1 Day - On Duty, 1 Day Off Duty policy.";

    // Fetch admins on duty (RoleID = 4 and ShiftID = 2)
    $adminQuery = "SELECT Email FROM personnel WHERE RoleID = 4 AND ShiftID = 2";
    $adminResult = $conn->query($adminQuery);

    if ($adminResult->num_rows > 0) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true); // Initialize $mail here

        try {
            // Enable verbose debugging
            $mail->SMTPDebug = 2; // 2 = Enable verbose debug output

            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'flintaxl.celetaria@gmail.com'; // Replace with your email
            $mail->Password = 'whif dedq ytly ryfo'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('flintaxl.celetaria@gmail.com', 'RescueFlow System'); // Replace with your email
            while ($admin = $adminResult->fetch_assoc()) {
                $mail->addAddress($admin['Email']); // Add each admin's email
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body = $emailBody;

            // Send the email
            $mail->send();
            echo "Email sent successfully!";
        } catch (Exception $e) {
            echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No admins on duty to notify.";
    }

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