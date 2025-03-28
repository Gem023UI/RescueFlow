<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Check if user is admin (role_id = 4)
if ($_SESSION['role'] != 4) {
    header("Location: ../dashboard/RescueFlowIndex.php");
    exit();
}

$schedule_id = $_GET['schedule_id'] ?? null;

if (!$schedule_id) {
    echo "<script>alert('No shift selected!'); window.location.href='ShiftsIndex.php';</script>";
    exit();
}

// Fetch the specific shift schedule
$query = $conn->prepare("SELECT ss.*, p.FirstName, p.LastName 
                        FROM shift_schedule ss
                        JOIN personnel p ON ss.PersonnelID = p.PersonnelID
                        WHERE ss.schedule_id = ?");
$query->bind_param("i", $schedule_id);
$query->execute();
$result = $query->get_result();
$shift = $result->fetch_assoc();

if (!$shift) {
    echo "<script>alert('Shift not found!'); window.location.href='ShiftsIndex.php';</script>";
    exit();
}

// Fetch all personnel for dropdown
$personnel = $conn->query("SELECT PersonnelID, FirstName, LastName FROM personnel ORDER BY LastName, FirstName");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shift Schedule</title>
    <link rel="stylesheet" href="ShiftsEdit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Edit Shift Schedule</h4>
        </div>
        <div class="card-body">
            <form action="ShiftsUpdate.php" method="post">
                <input type="hidden" name="schedule_id" value="<?= $schedule_id ?>">

                <div class="mb-3">
                    <label class="form-label">Personnel:</label>
                    <select name="PersonnelID" class="form-select" required>
                        <?php while ($p = $personnel->fetch_assoc()): ?>
                            <option value="<?= $p['PersonnelID'] ?>" <?= $p['PersonnelID'] == $shift['PersonnelID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['FirstName'] . ' ' . $p['LastName']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Time:</label>
                        <input type="time" name="start_time" class="form-control" value="<?= $shift['start_time'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Time:</label>
                        <input type="time" name="end_time" class="form-control" value="<?= $shift['end_time'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Shift Day:</label>
                    <select name="shift_day" class="form-select" required>
                        <option value="Monday" <?= $shift['shift_day'] == 'Monday' ? 'selected' : '' ?>>Monday</option>
                        <option value="Tuesday" <?= $shift['shift_day'] == 'Tuesday' ? 'selected' : '' ?>>Tuesday</option>
                        <option value="Wednesday" <?= $shift['shift_day'] == 'Wednesday' ? 'selected' : '' ?>>Wednesday</option>
                        <option value="Thursday" <?= $shift['shift_day'] == 'Thursday' ? 'selected' : '' ?>>Thursday</option>
                        <option value="Friday" <?= $shift['shift_day'] == 'Friday' ? 'selected' : '' ?>>Friday</option>
                        <option value="Saturday" <?= $shift['shift_day'] == 'Saturday' ? 'selected' : '' ?>>Saturday</option>
                        <option value="Sunday" <?= $shift['shift_day'] == 'Sunday' ? 'selected' : '' ?>>Sunday</option>
                    </select>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Update Shift</button>
                    <a href="ShiftsIndex.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>