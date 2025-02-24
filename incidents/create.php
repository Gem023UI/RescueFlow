<?php
ob_start(); // Prevent output before headers
require '../includes/config.php';
include '../includes/header.php';

// Fetch members for the dropdown with correct column names
$members_sql = "SELECT member_id, CONCAT(first_name, ' ', last_name) AS full_name FROM members ORDER BY first_name ASC";
$members_result = $conn->query($members_sql);

if (!$members_result) {
    die("Database error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Incident Report</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h1 class="mb-4">Create Incident Report</h1>
    <a href="index.php" class="btn btn-secondary mb-3">Back to List</a>
    
    <form action="index.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="incident_type" class="form-label">Incident Type</label>
            <input type="text" class="form-control" id="incident_type" name="incident_type" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div class="mb-3">
            <label for="reported_by" class="form-label">Reported By</label>
            <select class="form-control" id="reported_by" name="reported_by" required>
                <option value="">Select a Member</option>
                <?php while ($member = $members_result->fetch_assoc()): ?>
                    <option value="<?php echo $member['member_id']; ?>">
                        <?php echo htmlspecialchars($member['full_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Resolved">Resolved</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="attachments" class="form-label">Attachments</label>
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
    <script>
        // Bootstrap validation
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
ob_end_flush();
?>