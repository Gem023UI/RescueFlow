<?php
session_start();
include("../includes/config.php");
require '../vendor/autoload.php'; // Include PHPMailer if using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $firstName = trim($_POST['uname']);
    $lastName = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);

    // Check if passwords match
    if ($password !== $confirmPass) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: LoginRegister.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into Personnel table
    $sql = "INSERT INTO Personnel (FirstName, LastName, Email, Password, RoleID) VALUES (?, ?, ?, ?, 2)"; // Assuming RoleID = 2 for normal users
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $hashedPassword);
        if (mysqli_stmt_execute($stmt)) {
            // Send confirmation email
            $mail = new PHPMailer(true);
            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'flintaxl.celetaria@gmail.com';
                $mail->Password = 'whif dedq ytly ryfo'; // Use App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Sender & Recipient
                $mail->setFrom('flintaxl.celetaria@gmail.com', 'RescueFlow Dispatch');
                $mail->addAddress($email, "$firstName $lastName");

                // Email Content
                $mail->isHTML(true);
                $mail->Subject = 'Registration Successful - RescueFlow Dispatch';
                $mail->Body = "Hello $firstName,<br><br>Your registration at RescueFlow Dispatch was successful!<br>Login using your email: <b>$email</b>.<br><br>Best Regards,<br>RescueFlow Team";

                $mail->send();
                $_SESSION['message'] = "Registration successful! A confirmation email has been sent.";
            } catch (Exception $e) {
                $_SESSION['message'] = "Registration successful, but email could not be sent. Error: {$mail->ErrorInfo}";
            }

            mysqli_stmt_close($stmt);
            header("Location: LoginRegister.php");
            exit();
        } else {
            $_SESSION['message'] = "Registration failed.";
        }
    } else {
        $_SESSION['message'] = "Database error.";
    }

    header("Location: LoginRegister.php");
    exit();
}
?>