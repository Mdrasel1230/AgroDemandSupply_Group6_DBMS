<?php
$mysqli = new mysqli("localhost", "admin", "admin", "agriculture_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$query = "SELECT warehouseID, zone, location, capacity FROM WAREHOUSE";
$result = $mysqli->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$mysqli->close();
?>
