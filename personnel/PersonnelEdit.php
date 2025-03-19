<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Get the member details
$member_id = $_GET['member_id'] ?? null;
if (!$member_id) {
    die("Error: Invalid or missing member ID.");
}

$query = "SELECT first_name, last_name, email, phone, role_id, rank_id, image FROM members WHERE member_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $member_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $role_id = $row['role_id'];
    $rank_id = $row['rank_id'];
    $old_image = $row['image']; // Store the old image
} else {
    die("Error: Member not found.");
}
mysqli_stmt_close($stmt);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role_id = $_POST['role_id'];
    $rank_id = $_POST['rank_id'];

    $image = $old_image; // Default to the old image if no new one is uploaded

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../personnel/images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        // Check if an old image exists and delete it (excluding default.jpg)
        if ($old_image && $old_image !== "default.jpg" && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }

        // Move new uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = $image_name; // Update the image name for the database
        } else {
            echo "Error uploading new image.";
            exit();
        }
    }

    // Update database with new values
    $update_query = "UPDATE members SET first_name=?, last_name=?, email=?, phone=?, role_id=?, rank_id=?, image=? WHERE member_id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssiissi", $first_name, $last_name, $email, $phone, $role_id, $rank_id, $image, $member_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: PersonnelIndex.php?status=updated");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="PersonnelEdit.css">
    <script type="text/javascript" src="PersonnelIndex.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Personnel</title>
</head>
<body>
    <div class="personnel-container">
        <div class="personnel-header">EDIT PERSONNEL</div>
        <div class="personnel-container">
            <form method="POST" action="PersonnelUpdate.php" enctype="multipart/form-data" class="personnel-info">
                <input type="hidden" name="member_id" value="<?= htmlspecialchars($member_id) ?>">
                <div class="personnel-picture">
                    <img src="../personnel/images/<?= htmlspecialchars($old_image) ?>" alt="Profile Picture">
                    <div class="profile-input">
                        <input type="file" name="image" id="image-upload" accept="image/*">
                    </div>
                </div>
                <div class="personnel-form">
                    <div class="personnel-details">
                        <label>First Name</label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
                    </div>
                    <div class="personnel-details">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
                    </div>
                    <div class="personnel-details">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="personnel-details">
                        <label>Phone</label>
                        <input type="phone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>
                    <div class="personnel-details">
                        <label>Role</label>
                        <select name="role_id">
                            <option value="1" <?= $role_id == 1 ? 'selected' : '' ?>>Fire Fighter</option>
                            <option value="2" <?= $role_id == 2 ? 'selected' : '' ?>>Team Leader</option>
                            <option value="3" <?= $role_id == 3 ? 'selected' : '' ?>>Dispatcher</option>
                            <option value="4" <?= $role_id == 4 ? 'selected' : '' ?>>Administrator</option>
                        </select>
                    </div>
                    <div class="personnel-details">
                        <label>Rank</label>
                        <select name="rank_id">
                            <option value="1" <?= $rank_id == 1 ? 'selected' : '' ?>>Probationary Firefighter</option>
                            <option value="2" <?= $rank_id == 2 ? 'selected' : '' ?>>Firefighter First Class</option>
                            <option value="3" <?= $rank_id == 3 ? 'selected' : '' ?>>Lieutenant</option>
                            <option value="4" <?= $rank_id == 4 ? 'selected' : '' ?>>Captain</option>
                            <option value="5" <?= $rank_id == 5 ? 'selected' : '' ?>>Chief</option>
                        </select>
                    </div>
                    <div class="personnel-button">
                        <button type="submit" class="update-btn">Update</button>
                        <a href="PersonnelIndex.php" class="cancel-btn">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

