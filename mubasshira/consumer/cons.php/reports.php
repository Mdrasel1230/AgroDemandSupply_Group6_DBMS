<?php
include('db_config.php');

$sql = "SELECT productName, SUM(quantity) AS total_quantity FROM tbStorageSupply 
        INNER JOIN tbProduct ON tbStorageSupply.productID = tbProduct.productID 
        GROUP BY productName";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Reports</h1>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['productName']}</td><td>{$row['total_quantity']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>