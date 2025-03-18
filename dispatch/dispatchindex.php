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

        // Fetch on-duty members
        $onDutyQuery = "SELECT m.email FROM shifts s 
                        JOIN members m ON s.member_id = m.member_id 
                        WHERE NOW() BETWEEN s.start_time AND s.end_time";
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

            // Send email to each on-duty member
            while ($row = mysqli_fetch_assoc($onDutyResult)) {
                $mail->addAddress($row['email']);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                $mail->clearAddresses();  // Clear addresses for the next email
            }
            echo "<p style='color: green;'>Emails sent successfully!</p>";
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
        echo "<p style='color: green;'>Status updated successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
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

    $sql = "INSERT INTO emergency_details (dispatch_id, what, `where`, `why`, caller_name, caller_phone) 
    VALUES ('$dispatch_id', '$what', '$where', '$why', '$caller_name', '$caller_phone')";

    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>Emergency information saved successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
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
    <style>
        .container {
            max-width: 900px;
        }
        h2 {
            margin-top: 30px;
        }
        form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .alert {
            margin-top: 20px;
        }
        .carousel-item {
            padding: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <nav id="sidebar">
        <ul>
        <li>
            <span class="logo">BFP NCR Taguig S1</span>
            <button onclick=toggleSidebar() id="toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
            </button>
        </li>
        <li>
            <a href="../dashboard/RescueFlowIndex.php" >
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
            <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="../activities/ActivityIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v200h-80v-40H200v400h280v80H200Zm0-560h560v-80H200v80Zm0 0v-80 80ZM560-80v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-300L683-80H560Zm300-263-37-37 37 37ZM620-140h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/></svg>
            <span>Activities</span>
            </a>
        </li>
        <li>
            <a href="../incident/IncidentIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M240-400q0 52 21 98.5t60 81.5q-1-5-1-9v-9q0-32 12-60t35-51l113-111 113 111q23 23 35 51t12 60v9q0 4-1 9 39-35 60-81.5t21-98.5q0-50-18.5-94.5T648-574q-20 13-42 19.5t-45 6.5q-62 0-107.5-41T401-690q-39 33-69 68.5t-50.5 72Q261-513 250.5-475T240-400Zm240 52-57 56q-11 11-17 25t-6 29q0 32 23.5 55t56.5 23q33 0 56.5-23t23.5-55q0-16-6-29.5T537-292l-57-56Zm0-492v132q0 34 23.5 57t57.5 23q18 0 33.5-7.5T622-658l18-22q74 42 117 117t43 163q0 134-93 227T480-80q-134 0-227-93t-93-227q0-129 86.5-245T480-840Z"/></svg>
            <span>Incidents</span>
            </a>
        </li>
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M280-120q-50 0-85-35t-35-85h-40q-33 0-56.5-23.5T40-320v-200h440v-160q0-33 23.5-56.5T560-760h80v-40q0-17 11.5-28.5T680-840h40q17 0 28.5 11.5T760-800v40h22q26 0 47 15t29 40l58 172q2 6 3 12.5t1 13.5v267H800q0 50-35 85t-85 35q-50 0-85-35t-35-85H400q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T320-240q0-17-11.5-28.5T280-280q-17 0-28.5 11.5T240-240q0 17 11.5 28.5T280-200Zm400 0q17 0 28.5-11.5T720-240q0-17-11.5-28.5T680-280q-17 0-28.5 11.5T640-240q0 17 11.5 28.5T680-200ZM120-440v120h71q17-19 40-29.5t49-10.5q26 0 49 10.5t40 29.5h111v-120H120Zm440 120h31q17-19 40-29.5t49-10.5q26 0 49 10.5t40 29.5h71v-120H560v120Zm0-200h276l-54-160H560v160ZM40-560v-60h40v-80H40v-60h400v60h-40v80h40v60H40Zm100-60h70v-80h-70v80Zm130 0h70v-80h-70v80Zm210 180H120h360Zm80 0h280-280Z"/></svg>
            <span>Assets</span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
            </button>
            <ul class="sub-menu">
          <div>
            <li><a href="../assets/FireTruck1.php">Fire Truck 1</a></li>
            <li><a href="../assets/FireTruck2.php">Fire Truck 2</a></li>
            <li><a href="../assets/FireTruck3.php">Fire Truck 3</a></li>
            <li><a href="../assets/FireTruck4.php">Fire Truck 4</a></li>
            <li><a href="../assets/EmergencyVehicle.php">Emergency Vehicle</a></li>
            <li><a href="../assets/Stationary.php">Stationary</a></li>
          </div>
        </ul>
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M640-160v-280h160v280H640Zm-240 0v-640h160v640H400Zm-240 0v-440h160v440H160Z"/></svg>
            <span>Analysis</span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
            </button>
            <ul class="sub-menu">
            <div>
                <li><a href="../analysis/AnalysisCauses.php">Cause of Fire</a></li>
                <li><a href="../analysis/AnalysisHotspot.php">Fire Hotspot</a></li>
            </div>
            </ul>
        </li>
        </li>
        <li>
            <a href="../personnel/PersonnelIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M440-280h320v-22q0-45-44-71.5T600-400q-72 0-116 26.5T440-302v22Zm160-160q33 0 56.5-23.5T680-520q0-33-23.5-56.5T600-600q-33 0-56.5 23.5T520-520q0 33 23.5 56.5T600-440ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
            <span>Personnels</span>
            </a>
        </li>
        <li>
            <a href="../training/TrainingIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="m216-160-56-56 384-384H440v80h-80v-160h233q16 0 31 6t26 17l120 119q27 27 66 42t84 16v80q-62 0-112.5-19T718-476l-40-42-88 88 90 90-262 151-40-69 172-99-68-68-266 265Zm-96-280v-80h200v80H120ZM40-560v-80h200v80H40Zm739-80q-33 0-57-23.5T698-720q0-33 24-56.5t57-23.5q33 0 57 23.5t24 56.5q0 33-24 56.5T779-640Zm-659-40v-80h200v80H120Z"/></svg>
            <span>Training</span>
            </a>
        </li>
        </ul>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Firefighter Dispatch System</h1>

        <!-- Dispatch Location Form -->
        <div class="card p-4 mb-4">
            <h2>Dispatch Location</h2>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" class="form-control" name="location" placeholder="Enter Dispatch Location" required>
                </div>
                <button type="submit" name="submit_location" class="btn btn-success w-100">Dispatch</button>
            </form>
        </div>

        <!-- Update Dispatch Status Form -->
        <div class="card p-4 mb-4">
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
        <div class="card p-4 mb-4">
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
                <button type="submit" name="submit_emergency_info" class="btn btn-warning w-100">Save Emergency Info</button>
            </form>
        </div>

        <!-- Button to trigger emergency history view -->
        <div class="card p-4 mb-4">
            <h2>View Emergency History</h2>
            <button class="btn btn-info w-100" data-bs-toggle="collapse" data-bs-target="#emergencyHistory" aria-expanded="false" aria-controls="emergencyHistory">
                View Emergency History
            </button>

            <!-- Emergency History (Carousel) -->
            <div class="collapse mt-4" id="emergencyHistory">
                <div id="historyCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($emergencyHistory as $index => $record): ?>
                            <div class="carousel-item <?php echo ($index == 0) ? 'active' : ''; ?>">
                                <div class="card p-4">
                                    <h5><?php echo "Caller: " . htmlspecialchars($record['caller_name']); ?></h5>
                                    <p><strong>What:</strong> <?php echo htmlspecialchars($record['what']); ?></p>
                                    <p><strong>Where:</strong> <?php echo htmlspecialchars($record['where']); ?></p>
                                    <p><strong>Why:</strong> <?php echo htmlspecialchars($record['why']); ?></p>
                                    <p><strong>Caller Phone:</strong> <?php echo htmlspecialchars($record['caller_phone']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#historyCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#historyCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <!-- Scroll Buttons -->
                <div class="mt-3 text-center">
                    <button class="btn btn-primary" id="scrollPrev">Scroll Previous</button>
                    <button class="btn btn-primary" id="scrollNext">Scroll Next</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll to previous item in the carousel
        document.getElementById('scrollPrev').addEventListener('click', function () {
            var carousel = new bootstrap.Carousel(document.getElementById('historyCarousel'));
            carousel.prev();
        });

        // Scroll to next item in the carousel
        document.getElementById('scrollNext').addEventListener('click', function () {
            var carousel = new bootstrap.Carousel(document.getElementById('historyCarousel'));
            carousel.next();
        });
    </script>
</body>
</html>
