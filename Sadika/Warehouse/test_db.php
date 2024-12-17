<?php
$mysqli = new mysqli("localhost", "root", "", "agriculture_db");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "Database connected successfully!";
?>
