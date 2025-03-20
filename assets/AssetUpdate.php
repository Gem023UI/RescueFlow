<?php
session_start();
include('../includes/config.php');
include('../includes/restrict_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_id = $_POST['asset_id'];
    $assetcategory_id = $_POST['assetcategory_id'];
    $asset_name = $_POST['asset_name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $last_maintenance_date = !empty($_POST['last_maintenance_date']) ? $_POST['last_maintenance_date'] : NULL;

    $sql = "UPDATE assets SET assetcategory_id=?, asset_name=?, description=?, status=?, last_maintenance_date=? WHERE asset_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $assetcategory_id, $asset_name, $description, $status, $last_maintenance_date, $asset_id);
    $stmt->execute();
    $stmt->close();

    $img_query = "SELECT img_path FROM assets_image WHERE asset_id = ?";
    $stmt = $conn->prepare($img_query);
    $stmt->bind_param("i", $asset_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $filePath = "../" . $row['img_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    $stmt->close();

    $delete_img_sql = "DELETE FROM assets_image WHERE asset_id = ?";
    $stmt = $conn->prepare($delete_img_sql);
    $stmt->bind_param("i", $asset_id);
    $stmt->execute();
    $stmt->close();

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = "../assets/images/" . $fileName;

            if (move_uploaded_file($tmp_name, $targetPath)) {
                $imgPath = "assets/images/" . $fileName;
                $stmt = $conn->prepare("INSERT INTO assets_image (asset_id, img_path) VALUES (?, ?)");
                $stmt->bind_param("is", $asset_id, $imgPath);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    $conn->close();
    header("Location: FireTruck1.php");
    exit();
}
?>
