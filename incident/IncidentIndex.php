<?php
session_start();
include('../includes/config.php');
include('../dispatch/dispatchbutton.html');
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Handle Create & Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $incident_id = $_POST['incident_id'] ?? null;
    $incident_type = isset($_POST['incident_type']) ? trim($_POST['incident_type']) : '';
    $severity_id = $_POST['severity_id'] ?? null;
    $barangay_id = $_POST['barangay_id'] ?? null; // Use barangay_id instead of location

    // Ensure 'reported_by' is provided, or set a default value (e.g., "Anonymous")
    $reported_by = !empty($_POST['reported_by']) ? trim($_POST['reported_by']) : 'Anonymous'; 
    $status_id = isset($_POST['status']) ? $_POST['status'] : 'Pending'; 
    $actions_taken = isset($_POST['actions_taken']) ? trim($_POST['actions_taken']) : '';
    $attachments = [];
    $cause = isset($_POST['cause']) ? trim($_POST['cause']) : ''; 

    // Handle file uploads
    $upload_dir = '../uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['attachments']['name'][0])) {
        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['attachments']['error'][$key] == 0) {
                $file_name = time() . "_" . basename($_FILES['attachments']['name'][$key]);
                $file_path = $upload_dir . $file_name;
                if (move_uploaded_file($tmp_name, $file_path)) {
                    $attachments[] = $file_path;
                }
            }
        }
    }

    // Retrieve existing attachments if updating
    if ($incident_id) {
        $sql = "SELECT attachments FROM incidents WHERE incident_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $incident_id);
        $stmt->execute();
        $stmt->bind_result($existing_attachments);
        $stmt->fetch();
        $stmt->close();

        if (!empty($existing_attachments)) {
            $existing_files = explode(',', $existing_attachments);
            $attachments = array_merge($existing_files, $attachments);
        }
    }

    $attachments_string = !empty($attachments) ? implode(',', $attachments) : null;

    if ($incident_id) {
        // Update Incident
        $sql = "UPDATE incidents SET incident_type=?, severity_id=?, barangay_id=?, reported_by=?, status_id=?, actions_taken=?, cause=?, attachments=? WHERE incident_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississsi", $incident_type, $severity_id, $barangay_id, $reported_by, $status_id, $actions_taken, $cause, $attachments_string, $incident_id);
    } else {
        // Insert New Incident
        $sql = "INSERT INTO incidents (incident_type, severity_id, barangay_id, reported_by, status_id, actions_taken, cause, attachments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sississs", $incident_type, $severity_id, $barangay_id, $reported_by, $status_id, $actions_taken, $cause, $attachments_string);
    }

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $incident_id = intval($_GET['delete']);
    $sql = "DELETE FROM incidents WHERE incident_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $incident_id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: IncidentIndex.php");
        exit();
    }
}

// Restrict if not Admin Function
$user_id = $_SESSION['user_id'] ?? null;
$role_id = null;

if ($user_id) {
    $query = "SELECT role_id FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($role_id);
    $stmt->fetch();
    $stmt->close();
}

// Fetch incidents with barangay name instead of location
$sql = "SELECT i.*, 
               i.reported_by AS reporter_name, 
               s.level AS severity,
               st.status_name,
               i.address,
               b.barangay_name AS barangay
        FROM incidents i
        LEFT JOIN severity s ON i.severity_id = s.id
        LEFT JOIN status st ON i.status_id = st.status_id
        LEFT JOIN barangays b ON i.barangay_id = b.barangay_id 
        ORDER BY i.reported_time DESC";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BFP NCR Taguig City</title>
  <link rel="stylesheet" href="IncidentIndex.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="IncidentIndex.js" defer></script>
</head>
<body>
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
    <main>
    <h1 class="incident-header">INCIDENT REPORTS</h1>
    <?php if ($role_id == 4): // Only show for admin ?>
        <div class="admin-button">
            <a href="IncidentCreate.php" class="add-button">ADD INCIDENT</a>
            <a href="IncidentPDF.php" class="pdf-button">GENERATE PDF</a>
        </div>
    <?php endif; ?>
    <div class="incident-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="incident-structure">
                <div class="incident-picture">
                    <?php if (!empty($row['attachments'])): ?>
                    <?php 
                    $files = explode(',', $row['attachments']);
                    $firstFile = trim($files[0]);
                    $file_ext = strtolower(pathinfo($firstFile, PATHINFO_EXTENSION));
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                        <img src="<?php echo htmlspecialchars($firstFile); ?>" class="img-fluid rounded-start" alt="Incident Image" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 100%;">
                                <p class="text-muted mb-0">No Image Available</p>
                            </div>
                        <?php endif; ?>
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 100%;">
                                <p class="text-muted mb-0">No Attachments</p>
                            </div>
                        <?php endif; ?>
                </div>
                <div class="incident-info">
                    <h5 class="card-title">Incident ID: <?php echo htmlspecialchars($row['incident_id']); ?></h5>
                    <p class="card-text">
                        <strong>Type:</strong> <?php echo htmlspecialchars($row['incident_type']); ?><br>
                        <strong>Severity:</strong> <?php echo htmlspecialchars($row['severity'] ?? 'Not Specified'); ?><br>
                        <strong>Barangay:</strong> <?php echo htmlspecialchars($row['barangay']); ?><br>
                        <strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?><br>
                        <strong>Cause:</strong> <?php echo htmlspecialchars($row['cause'] ?? 'No cause recorded.'); ?><br>
                        <div class="incident-details">
                            <strong>Reported By:</strong> <?php echo htmlspecialchars($row['reporter_name']); ?><br>
                            <strong>Time:</strong> <?php echo htmlspecialchars($row['reported_time']); ?><br>
                        </div>
                    </p>
                    <?php if ($role_id == 4): // Only show for admin ?>
                        <div class="crud-button">
                            <a href="IncidentEdit.php?id=<?php echo $row['incident_id']; ?>" class="edit-button">Edit</a>
                            <a href="?delete=<?php echo $row['incident_id']; ?>" class="delete-button" onclick="return confirm('Are you sure?');">Delete</a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($row['attachments']) && count($files) > 1): ?>
                    <div class="incident-additional">
                        <strong>Additional Attachments:</strong><br>
                        <?php 
                        for ($i = 1; $i < count($files); $i++) {
                            $file = trim($files[$i]);
                            $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                echo '<img src="' . htmlspecialchars($file) . '" alt="Attachment" style="max-width: 50px; max-height: 50px; margin-right: 5px;">';
                                } else {
                                     echo '<a href="' . htmlspecialchars($file) . '" target="_blank">View File</a><br>';
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    </main>
</body>
</html>
