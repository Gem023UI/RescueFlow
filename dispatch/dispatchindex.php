<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Adjust this path if necessary

session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

// Fetch status options
$statusQuery = "SELECT * FROM status";
$statusResult = mysqli_query($conn, $statusQuery);
$statuses = [];
while ($row = mysqli_fetch_assoc($statusResult)) {
    $statuses[] = $row;
}

// Fetch emergency details for the carousel (swipable history)
$emergencyHistoryQuery = "SELECT * FROM emergency_details ORDER BY timestamp DESC";
$emergencyHistoryResult = mysqli_query($conn, $emergencyHistoryQuery);
$emergencyHistory = [];
while ($row = mysqli_fetch_assoc($emergencyHistoryResult)) {
    $emergencyHistory[] = $row;
}

// Handle Dispatch Submission
if (isset($_POST["submit_location"])) {
    $location = mysqli_real_escape_string($conn, $_POST["location"]);
    $status_id = 2; // Default to "In progress"
    $dispatched_unit = "Firetruck";

    $sql = "INSERT INTO dispatches (location, dispatched_unit, status_id) VALUES ('$location', '$dispatched_unit', '$status_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Location saved successfully!</p>";

        // Fetch on-duty personnel (statusID = 2)
        $onDutyQuery = "SELECT p.Email 
                        FROM personnel p 
                        JOIN shifts s ON p.ShiftID = s.shift_id 
                        WHERE s.status = 'On Duty'";  // Assuming 'On Duty' corresponds to statusID = 2
        $onDutyResult = mysqli_query($conn, $onDutyQuery);

        // Email details
        $subject = "🚨 Emergency Alert: Dispatch to $location";
        $message = "An emergency is ongoing at $location. Firetruck dispatched.\n\nPlease take necessary actions.";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'flintaxl.celetaria@gmail.com';
            $mail->Password = 'whif dedq ytly ryfo';  // App Password, NOT your actual password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('flintaxl.celetaria@gmail.com', 'RescueFlow Dispatch');
            $mail->isHTML(false);  // Plain text email

            // Send email to each on-duty personnel
            while ($row = mysqli_fetch_assoc($onDutyResult)) {
                $mail->addAddress($row['Email']);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                $mail->clearAddresses();  // Clear addresses for the next email
            }
            $_SESSION['success_message'] = "Personnels Notified Successfully!";
            header("Location: DispatchIndex.php");
        } catch (Exception $e) {
            echo "<p style='color: red;'>Mailer Error: " . $mail->ErrorInfo . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Handle status updates
if (isset($_POST["update_status"])) {
    $dispatch_id = $_POST["dispatch_id"];
    $new_status = $_POST["status"];

    $updateSql = "UPDATE dispatches SET status_id = '$new_status' WHERE disp_id = '$dispatch_id'";

    if (mysqli_query($conn, $updateSql)) {
        $_SESSION['success_message'] = "Status Updated Successfully!";
        header("Location: DispatchIndex.php");
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
        header("Location: DispatchIndex.php");
    }
}

// Handle emergency data entry
if (isset($_POST['submit_emergency_info'])) {
    $what = mysqli_real_escape_string($conn, $_POST['what']);
    $where = mysqli_real_escape_string($conn, $_POST['where']);
    $why = mysqli_real_escape_string($conn, $_POST['why']);
    $caller_name = mysqli_real_escape_string($conn, $_POST['caller_name']);
    $caller_phone = mysqli_real_escape_string($conn, $_POST['caller_phone']);
    $dispatch_id = $_POST['dispatch_id']; 
    $status = mysqli_real_escape_string($conn, $_POST['status']); // Get the status from the form

    // Validate the status value
    $statusCheckQuery = "SELECT status_id FROM status WHERE status_id = '$status'";
    $statusCheckResult = mysqli_query($conn, $statusCheckQuery);

    if (mysqli_num_rows($statusCheckResult) > 0) {
        // Include the status in the SQL query
        $sql = "INSERT INTO emergency_details (dispatch_id, what, `where`, `why`, caller_name, caller_phone, status) 
                VALUES ('$dispatch_id', '$what', '$where', '$why', '$caller_name', '$caller_phone', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "<p style='color: green;'>Emergency information saved successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Error: Invalid status value.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firefighter Dispatch System</title>
    <link rel="stylesheet" href="DispatchIndex.css">
    <script type="text/javascript" src="DispatchIndex.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="dispatch-container">
        <h1 class="dispatch-header">DISPATCH ON GOING</h1>
        <!-- Dispatch Location Form -->
        <div class="dispatch-form">
            <h2>Dispatch Location</h2>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" name="location" placeholder="Enter Dispatch Location" required>
                </div>
                <button type="submit" name="submit_location" class="btn btn-success w-100">Dispatch</button>
            </form>
        </div>

        <!-- Update Dispatch Status Form -->
        <div class="dispatch-form">
            <h2>Update Dispatch Status</h2>
            <form method="POST">
                <div class="mb-3">
                    <select name="dispatch_id" class="form-select" required>
                        <?php
                        $dispatchQuery = "SELECT * FROM dispatches WHERE status_id != 3 ORDER BY dispatched_at DESC";
                        $dispatchResult = mysqli_query($conn, $dispatchQuery);
                        while ($dispatch = mysqli_fetch_assoc($dispatchResult)) {
                            echo "<option value='{$dispatch['disp_id']}'>{$dispatch['location']} (Status: {$dispatch['status_id']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <select name="status" class="form-select" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['status_id']; ?>"><?php echo $status['status_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="update_status" class="btn btn-primary w-100">Update Status</button>
            </form>
        </div>

        <!-- Emergency Details Form -->
        <div class="dispatch-form">
            <h2>Emergency Details Input</h2>
            <form method="POST">
                <input type="hidden" name="dispatch_id" value="1">
                <div class="mb-3">
                    <label for="what">What happened?</label>
                    <textarea name="what" placeholder="Describe the emergency" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="where">Where did it happen?</label>
                    <textarea name="where" placeholder="Provide the location" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="why">Why is this an emergency?</label>
                    <textarea name="why" placeholder="Explain the situation" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="caller_name">Caller Name</label>
                    <input type="text" class="form-control" name="caller_name" placeholder="Enter caller's name" required>
                </div>
                <div class="mb-3">
                    <label for="caller_phone">Caller Phone Number</label>
                    <input type="text" class="form-control" name="caller_phone" placeholder="Enter caller's phone number" required>
                </div>
                <!-- Add a status dropdown -->
                <div class="mb-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-select" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['status_id']; ?>"><?php echo $status['status_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="submit_emergency_info" class="btn btn-warning w-100">Save Emergency Info</button>
            </form>
        </div>
        <a href="../dashboard/RescueFlowIndex.php" class="btn btn-warning w-100">GO BACK</a>
    </div>
    </div>
</body>
</html>
