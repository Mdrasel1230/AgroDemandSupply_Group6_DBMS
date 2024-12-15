<?php
$servername = "localhost";
$username = "root";
$password = ""; // Update based on your database setup
$dbname = "agriculture_db"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>