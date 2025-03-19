<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Fetch roles and ranks for dropdowns
$roles = $conn->query("SELECT role_id, role_name FROM roles");
$ranks = $conn->query("SELECT rank_id, rank_name FROM ranks");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $rank_id = $_POST['rank_id'];
    $role_id = $_POST['role_id'];

    // Insert data into the members table
    $sql = "INSERT INTO members (first_name, last_name, email, phone, rank_id, role_id) 
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$rank_id', '$role_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../dashboard/RescueFlowIndex.php");
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
            <form method="POST" class="personnel-info" action="PersonnelStore.php" enctype="multipart/form-data">
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