<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assetcategory_ID = $_POST['assetcategory_ID'];
    $name = $_POST['asset_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $last_maintenance_date = !empty($_POST['last_maintenance_date']) ? $_POST['last_maintenance_date'] : NULL;

    // Insert asset details first
    $sql = "INSERT INTO assets (assetcategory_ID, asset_name, description, status, last_maintenance_date) 
            VALUES ('$assetcategory_ID', '$name', '$description', '$status', '$last_maintenance_date')";
    
    if ($conn->query($sql) === TRUE) {
        $asset_id = $conn->insert_id; // Get last inserted asset ID
        
        // Handle multiple file uploads
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = "../assets/images/"; // Ensure this directory exists
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                $fileName = basename($_FILES['images']['name'][$key]);
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($tmp_name, $targetPath)) {
                    // Save file path in the database
                    $imgPath = "assets/images/" . $fileName;
                    $conn->query("INSERT INTO assets_image (asset_id, img_path) VALUES ('$asset_id', '$imgPath')");
                }
            }
        }
        
        header("Location: FireTruck1.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
