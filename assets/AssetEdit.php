<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

$id = $_GET['id'];
$sql = "SELECT * FROM assets WHERE asset_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$asset = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
    <link rel="stylesheet" href="AssetEdit.css">
    <script type="text/javascript" src="AssetIndex.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="asset-container">
        <h1 class="asset-header">EDIT ASSET</h1>
        <form class="asset-info" action="AssetUpdate.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="asset_id" value="<?php echo $asset['asset_id']; ?>">
            <div class="asset-picture">
                <?php
                $asset_id = $asset['asset_id'];
                $img_query = "SELECT * FROM assets_image WHERE asset_id = $asset_id";
                $img_result = $conn->query($img_query);
                while ($img_row = $img_result->fetch_assoc()) {
                    echo '<img src="../' . $img_row['img_path'] . '" width="100" height="100" style="margin:5px;">';
                }
                ?><br>
                <input type="file" name="images[]" multiple><br>
            </div>
            <div class="asset-details">
                <label>Name:</label>
                <input type="text" name="asset_name" value="<?php echo htmlspecialchars($asset['asset_name']); ?>" required><br>
                <label>Category:</label>
                <select name="assetcategory_id">
                    <option value="1" <?php if ($asset['assetcategory_id'] == 1) echo 'selected'; ?>>Fire Truck 1</option>
                    <option value="2" <?php if ($asset['assetcategory_id'] == 2) echo 'selected'; ?>>Fire Truck 2</option>
                    <option value="3" <?php if ($asset['assetcategory_id'] == 3) echo 'selected'; ?>>Fire Truck 3</option>
                    <option value="4" <?php if ($asset['assetcategory_id'] == 4) echo 'selected'; ?>>Fire Truck 4</option>
                    <option value="6" <?php if ($asset['assetcategory_id'] == 6) echo 'selected'; ?>>Stationary</option>
                    <option value="7" <?php if ($asset['assetcategory_id'] == 7) echo 'selected'; ?>>Emergency Vehicle</option>
                </select><br>
                <label>Status:</label>
                <select name="status">
                    <option value="Available" <?php if ($asset['status'] == 'Available') echo 'selected'; ?>>Available</option>
                    <option value="In Use" <?php if ($asset['status'] == 'In Use') echo 'selected'; ?>>In Use</option>
                    <option value="Maintenance" <?php if ($asset['status'] == 'Maintenance') echo 'selected'; ?>>Maintenance</option>
                    <option value="Damaged" <?php if ($asset['status'] == 'Damaged') echo 'selected'; ?>>Damaged</option>
                </select><br>
                <label>Previous Maintenance:</label>
                <input type="date" name="last_maintenance_date" value="<?php echo $asset['last_maintenance_date']; ?>"><br>
                <label>Description:</label>
                <textarea name="description"><?php echo htmlspecialchars($asset['description']); ?></textarea><br>
                <div class="asset-button">
                    <button type="submit" class="update-btn">SUBMIT</button>
                    <a href="FireTruck1.php" class="cancel-btn">CANCEL</a>
                </div>
            </div>
        </form>    
    </div>
</body>
</html>
<?php
$conn->close(); // Close connection at the end
?>