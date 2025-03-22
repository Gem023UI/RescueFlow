<?php
session_start();
echo "Session ID: " . session_id() . "<br>";
print_r($_SESSION);
?>
