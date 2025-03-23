<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
include('../includes/config.php');
require '../vendor/autoload.php'; // Ensure you have included the PHPMailer autoload file

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['attendance_id']) && isset($_GET['time_out'])) {
    $attendanceId = intval($_GET['attendance_id']);
    $newTimeOut = $_GET['time_out'];

    // Fetch the email of the personnel associated with this attendance record
    $query = "SELECT p.Email FROM personnel p 
              JOIN attendance a ON p.PersonnelID = a.personnel_id 
              WHERE a.attendance_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $attendanceId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'No personnel found for this attendance record']);
        exit;
    }
    $row = $result->fetch_assoc();
    $personnelEmail = $row['Email'];
    $stmt->close();

    // Update the Time Out column in the attendance table
    $query = "UPDATE attendance SET time_out = ? WHERE attendance_id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("si", $newTimeOut, $attendanceId);

    if ($stmt->execute()) {
        // Email details
        $subject = "Attendance Verified";
        $message = "Attendance verified. Designated TIME OUT is at $newTimeOut.";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Enable verbose debugging
            $mail->SMTPDebug = 2; // 2 = Enable verbose debug output

            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'flintaxl.celetaria@gmail.com'; // Replace with your email
            $mail->Password = 'whif dedq ytly ryfo';  // Replace with your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('flintaxl.celetaria@gmail.com', 'Attendance System'); // Replace with your email and name
            $mail->isHTML(false);  // Plain text email

            // Send email to the personnel
            $mail->addAddress($personnelEmail);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>