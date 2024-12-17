<?php
include 'db_connection.php';

$query = "SELECT * FROM PRODUCT";
$result = $mysqli->query($query);

if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(["error" => "Failed to fetch product data"]);
}

$mysqli->close();
?>
