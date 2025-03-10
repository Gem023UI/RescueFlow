<?php
session_start();
include('../includes/config.php');
include('../button/button.html');

if (!isset($conn)) {
    die("Database connection failed.");
}

// Fetch facilitators
$facilitators = $conn->query("SELECT FacilitatorID, Name FROM facilitator");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $facilitator = $_POST['facilitator'];
    $dueDate = $_POST['dueDate'];
    $dueTime = $_POST['dueTime'];
    $setting = $_POST['setting'];
    $targetDir = "../media/activities/";
    $filePaths = [];

    // Handle file uploads
    foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['attachments']['error'][$key] == 0) {
            $fileName = basename($_FILES['attachments']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            move_uploaded_file($tmpName, $targetFilePath);
            $filePaths[] = $targetFilePath;
        }
    }

    $attachments = implode(",", $filePaths);
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO activity (Title, Description, Facilitator, DueDate, DueTime, Setting, Attachments) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $title, $description, $facilitator, $dueDate, $dueTime, $setting, $attachments);
    $stmt->execute();
    
    echo "<script>alert('Activity added successfully!'); window.location.href='ActivityIndex.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activities</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; flex-direction: column; align-items: center; }
        .floating-btn { width: 60px; height: 60px; background-color: orangered; border-radius: 50%; color: white; position: fixed; right: 30px; bottom: 30px; border: none; cursor: pointer; }
        .form-container { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3); border-radius: 10px; }
        .close-btn { cursor: pointer; color: red; float: right; }
    </style>
</head>
<body>
    <button class="floating-btn" onclick="document.getElementById('form-container').style.display='block'">+</button>
    <div id="form-container" class="form-container">
        <span class="close-btn" onclick="document.getElementById('form-container').style.display='none'">&times;</span>
        <h2>Add Activity</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required><br>
            <label>Description:</label>
            <textarea name="description" required></textarea><br>
            <label>Facilitator:</label>
            <select name="facilitator" required>
                <?php while ($row = $facilitators->fetch_assoc()) { ?>
                    <option value="<?= $row['FacilitatorID'] ?>"><?= $row['Name'] ?></option>
                <?php } ?>
            </select><br>
            <label>Due Date:</label>
            <input type="date" name="dueDate" required><br>
            <label>Due Time:</label>
            <input type="time" name="dueTime" required><br>
            <label>Setting:</label>
            <input type="text" name="setting" required><br>
            <label>Attachments:</label>
            <input type="file" name="attachments[]" multiple><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>