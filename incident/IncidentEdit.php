<?php
ob_start(); // Prevent output before headers
require '../includes/config.php';
include('../includes/restrict_admin.php');

// Get incident ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid incident ID.");
}
$incident_id = intval($_GET['id']);

// Fetch incident details including barangay
$sql = "SELECT i.incident_id, i.incident_type, i.barangay_id, i.reported_by, i.severity_id, i.cause, i.address, s.level as severity_level
        FROM incidents i
        LEFT JOIN severity s ON i.severity_id = s.id
        WHERE i.incident_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $incident_id);
$stmt->execute();
$result = $stmt->get_result();
$incident = $result->fetch_assoc();
$stmt->close();

if (!$incident) {
    die("Incident not found.");
}

// Fetch members for dropdown
$members_sql = "SELECT PersonnelID, CONCAT(FirstName, ' ', LastName) AS full_name FROM personnel ORDER BY FirstName ASC";
$members_result = $conn->query($members_sql);

// Fetch severity levels for dropdown
$severity_sql = "SELECT id, level FROM severity ORDER BY id ASC";
$severity_result = $conn->query($severity_sql);

// Fetch barangays for dropdown
$barangay_sql = "SELECT barangay_id, barangay_name FROM barangays ORDER BY barangay_name ASC";
$barangay_result = $conn->query($barangay_sql);

// Fetch ENUM values for cause
$cause_enum_query = "SHOW COLUMNS FROM incidents LIKE 'cause'";
$cause_enum_result = $conn->query($cause_enum_query);
$cause_enum_row = $cause_enum_result->fetch_assoc();
preg_match_all("/'([^']*)'/", $cause_enum_row['Type'], $matches);
$causes = $matches[1]; // Extract ENUM values

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident Report</title>
    <link rel="stylesheet" href="IncidentEdit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="IncidentIndex.js" defer></script>
</head>
<body">
    <div class="incident-container">
        <h1 class="incident-header">EDIT INCIDENT REPORT</h1>
        <form action="IncidentUpdate.php" method="POST" class="incident-info" novalidate>
            <input type="hidden" name="incident_id" value="<?php echo $incident['incident_id']; ?>">
            <!-- Incident, Severity Type -->
            <div class="incident-details">
                <label for="incident_type" class="form-label"><strong>Incident Type</strong></label>
                <input type="text" class="form-control" id="incident_type" name="incident_type" value="<?php echo htmlspecialchars($incident['incident_type']); ?>" required>
                <label for="severity_id" class="form-label"><strong>Severity</strong></label>
                <select class="form-control" id="severity_id" name="severity_id" required>
                    <option value="">Select Severity</option>
                    <?php while ($severity = $severity_result->fetch_assoc()): ?>
                        <option value="<?php echo $severity['id']; ?>" <?php echo ($incident['severity_id'] == $severity['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($severity['level']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>  
            <!-- Address, Baranggay -->
            <div class="incident-details">
            <label for="address" class="form-label"><strong>Address</strong></label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($incident['address']); ?>" required>
                <label for="barangay_id" class="form-label"><strong>Baranggay</strong></label>
                <select class="form-control" id="barangay_id" name="barangay_id" required>
                    <option value="">Select Barangay</option>
                    <?php while ($barangay = $barangay_result->fetch_assoc()): ?>
                        <option value="<?php echo $barangay['barangay_id']; ?>" 
                            <?php echo ($incident['barangay_id'] == $barangay['barangay_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($barangay['barangay_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- reporter, cause -->
            <div class="incident-details">
                <label for="reported_by" class="form-label"><strong>Reported By</strong></label>
                <input type="text" class="form-control" id="reported_by" name="reported_by" value="<?php echo htmlspecialchars($incident['reported_by'] ?? ''); ?>" required>
                <label for="cause" class="form-label"><strong>Cause</strong></label>
                <select class="form-control" id="cause" name="cause" required>
                    <option value="">Select Cause</option>
                    <?php foreach ($causes as $cause_option): ?>
                        <option value="<?php echo htmlspecialchars($cause_option); ?>" <?php echo ($incident['cause'] == $cause_option) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cause_option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="incident-button">
                <button type="submit" class="update-btn">UPDATE</button>
                <a href="IncidentIndex.php" class="cancel-btn">CANCEL</a>
            </div>
        </form>
    </div>
    <script>
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script> 
</body>
</html>