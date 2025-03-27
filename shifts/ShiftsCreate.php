<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if user is admin (role_id = 4)
if ($_SESSION['role'] != 4) {
    header("Location: ../dashboard/RescueFlowIndex.php");
    exit();
}

// Fetch all personnel
$personnel = $conn->query("SELECT PersonnelID, FirstName, LastName FROM personnel ORDER BY LastName, FirstName");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Shift Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Add Shift Schedule</h4>
        </div>
        <div class="card-body">
            <form action="ShiftsStore.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Personnel:</label>
                    <select name="PersonnelID" class="form-select" required>
                        <option value="">Select Personnel</option>
                        <?php while ($p = $personnel->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($p['PersonnelID']) ?>">
                                <?= htmlspecialchars($p['FirstName'] . ' ' . $p['LastName']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Time:</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time:</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Shift Day:</label>
                    <select name="shift_day" class="form-select" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select name="status" class="form-select" required>
                        <option value="Pending">Pending</option>
                        <option value="On Duty" selected>On Duty</option>
                        <option value="Off Duty">Off Duty</option>
                    </select>
                </div>

                <input type="hidden" name="assigned_by" value="<?= isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : '' ?>">

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Add Shift Schedule</button>
                    <a href="ShiftsIndex.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>