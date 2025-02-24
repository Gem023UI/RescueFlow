<?php
session_start(); // Prevent output before headers
include('../includes/config.php');
include('../includes/header.php');

    if (isset($_POST["submit_location"])){
        $location = $_POST["location"];
        ?>

        <iframe width="100%" height="500" src="https://maps.google.com/maps?q=<?php echo $location; ?>&output=embed">
        </iframe>

        <?php
    }
?>

<form method="POST">
    <p>
        <input type="text" name="location" placeholder="Enter Dispatch Location">
    </p>
    <input type="submit" name="submit_location">
</form>
