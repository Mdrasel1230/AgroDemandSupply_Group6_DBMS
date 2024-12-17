<?php
include 'db_connection.php';

$query = "SELECT * FROM WAREHOUSE";
$result = $mysqli->query($query);

if ($result) {
    $warehouses = [];
    while ($row = $result->fetch_assoc()) {
        $warehouses[] = $row;
    }
    echo json_encode($warehouses);
} else {
    echo json_encode(["error" => "Failed to fetch warehouse data"]);
}

$mysqli->close();
?>
