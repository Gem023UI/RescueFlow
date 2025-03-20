<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all required fields are set
    if (isset($_POST['PersonnelID'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['role_id'], $_POST['rank_id'], $_POST['shift_id'])) {
        
        // Sanitize inputs
        $personnel_id = intval($_POST['PersonnelID']);
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $role_id = intval($_POST['role_id']);
        $rank_id = intval($_POST['rank_id']);
        $shift_id = intval($_POST['shift_id']);

        // Handle image upload
        $image = $_POST['old_image']; // Default to the old image
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../personnels/profiles/";
            $image_name = basename($_FILES["image"]["name"]);
            $image_file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $unique_image_name = uniqid() . "." . $image_file_type; // Generate a unique name for the image
            $target_file = $target_dir . $unique_image_name;

            // Check if the file is an actual image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                die("Error: File is not an image.");
            }

            // Check file size (e.g., 5MB limit)
            if ($_FILES["image"]["size"] > 5000000) {
                die("Error: File is too large.");
            }

            // Allow only certain file formats
            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($image_file_type, $allowed_types)) {
                die("Error: Only JPG, JPEG, PNG, and GIF files are allowed.");
            }

            // Check if an old image exists and delete it (excluding default.jpg)
            if ($_POST['old_image'] && $_POST['old_image'] !== "default.png" && file_exists($target_dir . $_POST['old_image'])) {
                unlink($target_dir . $_POST['old_image']);
            }

            // Move new uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $unique_image_name; // Update the image name for the database
            } else {
                echo "Error uploading new image.";
                exit();
            }
        }

        // Update query using prepared statements
        $query = "UPDATE Personnel SET FirstName = ?, LastName = ?, Email = ?, PhoneNumber = ?, RoleID = ?, RankID = ?, ShiftID = ?, Profile = ? WHERE PersonnelID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssiiisi", $first_name, $last_name, $email, $phone, $role_id, $rank_id, $shift_id, $image, $personnel_id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            // Redirect to index.php with success message
            header("Location: PersonnelIndex.php?status=success");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Missing required fields.";
    }
} else {
    echo "Invalid request method.";
}
?>