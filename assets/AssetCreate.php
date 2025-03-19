<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assetcategory_ID = $_POST['assetcategory_ID'];
    $asset_name = $_POST['asset_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $last_maintenance_date = $_POST['last_maintenance_date'];

    // Validate required fields
    if (empty($asset_name) || empty($status)) {
        echo "All required fields must be filled!";
    } else {
        // Insert into database
        $sql = "INSERT INTO assets (asset_name, description, status, last_maintenance_date) VALUES ('$asset_name', '$description', '$status', '$last_maintenance_date')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Asset added successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Incident Report</title>
    <link rel="stylesheet" href="AssetCreate.css">
    <script type="text/javascript" src="AssetIndex.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="asset-container">
    <h1 class="asset-header">INSERT ASSET</h1>
        <form class="asset-info" action="AssetStore.php" method="post" enctype="multipart/form-data">
            <div class="asset-details">
                <label for="asset_name">Asset Name:</label>
                <input type="text" name="asset_name" required><br>
                <label for="assetcategory_ID">Category:</label>
                <select name="status">
                    <option value="Available">Available</option>
                    <option value="In Use">In Use</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Damaged">Damaged</option>
                </select><br>
                <label for="assetcategory_ID">Category:</label>
                <select name="assetcategory_ID">
                    <option value="1">Fire Truck 1</option>
                    <option value="2">Fire Truck 2</option>
                    <option value="3">Fire Truck 3</option>
                    <option value="4">Fire Truck 4</option>
                    <option value="6">Stationary</option>
                    <option value="7">Emergency Vehicle</option>
                </select><br>
                <label for="last_maintenance_date">Previous Maintenance:</label>
                <input type="date" name="last_maintenance_date"><br>
            </div>
            <div class="asset-details">
                <label for="description">Description:</label>
                <textarea name="description"></textarea><br>
            </div>
            <div class="asset-picture">
                <label for="attachments" class="form-label"><strong>Attachments</strong></label>
                <input type="file" class="form-control" id="attachments" name="images[]" multiple>
                <?php if (!empty($incident['attachments'])): ?>
                    <p>Existing Attachments:</p>
                    <?php
                    $files = explode(',', $incident['attachments']);
                    foreach ($files as $file): ?>
                        <a href="<?php echo htmlspecialchars($file); ?>" target="_blank">View File</a><br>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="asset-button">
                <button type="submit" class="submit-btn">SUBMIT</button>
                <a href="FireTruck1.php" class="cancel-btn">CANCEL</a>
            </div>
        </form>
    </div>
</body>
</html>
