<?php
session_start();
include("../includes/config.php");

// Sanitize and fetch form data
$email = trim($_POST['email']);
$first_name = trim($_POST['uname']); // First name
$last_name = trim($_POST['lastname']); // Last name
$password = trim($_POST['password']);
$rank_id = 1; // Default rank for new personnel
$shift_id = 1; // Default shift for new personnel
$role_id = 1; // Default role for new personnel

// Validate email format
if (!preg_match("/^\w+@\w+\w+/", $email)) {
    $_SESSION['message'] = 'Invalid email format';
    header("Location: LoginRegister.php");
    exit();
} 

// Validate password length
if (strlen($password) < 3) {
    $_SESSION['message'] = 'Password should be at least 3 characters';
    header("Location: LoginRegister.php");
    exit();
}

// Validate password match
$confirmPass = trim($_POST['confirmPass']);
if ($password !== $confirmPass) {
    $_SESSION['message'] = 'Passwords do not match';
    header("Location: LoginRegister.php");
    exit();
}

// Hash the password for secure storage
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new personnel into the Personnel table
$sql = "INSERT INTO Personnel (FirstName, LastName, Email, Password, RankID, ShiftID, RoleID) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssiii", $first_name, $last_name, $email, $hashed_password, $rank_id, $shift_id, $role_id);
    $execute = mysqli_stmt_execute($stmt);

    if ($execute) {
        $_SESSION['message'] = 'Registration successful';
        header("Location: LoginRegister.php");
        exit();
    } else {
        $_SESSION['message'] = 'Error during registration: ' . mysqli_error($conn);
        header("Location: LoginRegister.php");
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['message'] = 'Database error: Unable to prepare statement';
    header("Location: LoginRegister.php");
    exit();
}
?>