<?php
include 'db_connection.php';

$query = "
    SELECT 
        s.supplyID, s.quantity, s.unitPrice, s.storageSupplyDate, p.productName, w.zone, w.location
    FROM STORAGE_SUPPLY s
    JOIN PRODUCT p ON s.productID = p.productID
    JOIN WAREHOUSE w ON s.warehouseID = w.warehouseID
";

$result = $mysqli->query($query);

if ($result) {
    $supplyData = [];
    while ($row = $result->fetch_assoc()) {
        $supplyData[] = $row;
    }
    echo json_encode($supplyData);
} else {
    echo json_encode(["error" => "Failed to fetch supply data"]);
}

$mysqli->close();
?>
