<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $training_name = $_POST['training_name'];
    $description = $_POST['description'];
    $scheduled_date = $_POST['scheduled_date'];

    // Insert data into the trainings table
    $sql = "INSERT INTO trainings (training_name, description, scheduled_date) 
            VALUES ('$training_name', '$description', '$scheduled_date')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../trainings/index.php");
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
    <title>Edit Incident Report</title>
    <link rel="stylesheet" href="TrainingCreate.css">
</head>
<body>
    <div class="training-container">
        <h2 class="training-header">ADD NEW TRAINING</h2>
        <div class="training-info">
            <form method="POST" action="TrainingStore.php">
                <div class="training-details">
                    <label>Training Name:</label>
                    <input type="text" name="training_name" id="training_name" required><br>
                </div>
                <div class="training-details">
                    <label>Scheduled Date:</label>
                    <input type="date" name="scheduled_date" id="scheduled_date" required><br>
                </div>
                <div class="training-details">
                    <label>Description:</label>
                    <textarea name="description" id="description" required></textarea><br>
                </div>
                <div class="training-button">
                    <button type="submit" class="update-btn">Save</button>
                    <a href="TrainingIndex.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
