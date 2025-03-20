<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Fetch roles, ranks, and shifts for dropdowns
$roles = $conn->query("SELECT role_id, role_name FROM roles");
$ranks = $conn->query("SELECT rank_id, rank_name FROM ranks");
$shifts = $conn->query("SELECT shift_id, shift_day, start_time, end_time FROM shifts");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $rank_id = $_POST['rank_id'];
    $role_id = $_POST['role_id'];
    $shift_id = $_POST['shift_id'];
    $password = $_POST['password']; // Get the password from the form

    // Handle image upload
    $image = "default.jpg"; // Default image if no file is uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../personnel/images/";
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

        // Move new uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $unique_image_name; // Update the image name for the database
        } else {
            echo "Error uploading new image.";
            exit();
        }
    }

    // Hash the password for secure storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the Personnel table
    $sql = "INSERT INTO Personnel (FirstName, LastName, Email, PhoneNumber, RankID, RoleID, ShiftID, Profile, Password) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$rank_id', '$role_id', '$shift_id', '$image', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: PersonnelIndex.php"); // Redirect to PersonnelIndex.php after successful insertion
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Personnel</title>
    <link rel="stylesheet" href="PersonnelCreate.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="PersonnelIndex.js" defer></script>
</head>
<body>
    <div class="personnel-container">
        <div class="personnel-header">ADD PERSONNEL</div>
        <div class="personnel-container">
            <form method="POST" class="personnel-info" action="PersonnelCreate.php" enctype="multipart/form-data">
                <div class="personnel-picture">
                    <input type="file" name="image" id="image" class="custom-file-upload" accept="image/*">
                </div>
                <div class="personnel-details">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                </div>
                <div class="personnel-details">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>
                <div class="personnel-details">
                    <label for="rank_id" class="form-label">Rank</label>
                    <select name="rank_id" id="rank_id" class="form-select" required>
                        <option value="">Select Rank</option>
                        <?php while ($rank = $ranks->fetch_assoc()): ?>
                            <option value="<?= $rank['rank_id']; ?>"><?= $rank['rank_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="role_id" class="form-label">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">Select Role</option>
                        <?php while ($role = $roles->fetch_assoc()): ?>
                            <option value="<?= $role['role_id']; ?>"><?= $role['role_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <label for="shift_id" class="form-label">Shift</label>
                    <select name="shift_id" id="shift_id" class="form-select" required>
                        <option value="">Select Shift</option>
                        <?php while ($shift = $shifts->fetch_assoc()): ?>
                            <option value="<?= $shift['shift_id']; ?>">
                                <?= $shift['shift_day'] . " (" . $shift['start_time'] . " - " . $shift['end_time'] . ")"; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="personnel-details">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="personnel-button">
                    <button type="submit" class="submit-btn">Add</button>
                    <a href="PersonnelIndex.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>