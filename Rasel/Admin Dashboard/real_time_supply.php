<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Real-Time Supply Levels</title>
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Real-Time Supply Levels</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <?php include 'db_connect.php'; ?>

    <main>
    <section>
        <h1>Real-Time Supply Levels</h1>

        <?php
        // Initialize variables
        $editMode = false;
        $editData = [];
        $selectedSupplyType = $_POST['tableType'] ?? '';
        $selectedWarehouseRetailID = $_POST['warehouseRetailID'] ?? '';
        $selectedProductID = $_POST['productID'] ?? '';

        // Handle Edit Request
        if (isset($_GET['edit']) && isset($_GET['table'])) {
            $editMode = true;
            $id = $_GET['edit'];
            $table = $_GET['table'];
            $idColumn = ($table == "STORAGE_SUPPLY") ? "supplyID" : "retailSupplyID";

            $editSql = "SELECT * FROM $table WHERE $idColumn = '$id'";
            $editResult = $conn->query($editSql);
            if ($editResult) {
                $editData = $editResult->fetch_assoc();
                $selectedSupplyType = $table; // Automatically set the supply type for editing
            }
        }

        // Fetch dynamic options based on Supply Type
        $warehouseRetailOptions = [];
        if ($selectedSupplyType === "STORAGE_SUPPLY") {
            $warehouseQuery = "SELECT warehouseID AS id, location AS name FROM WAREHOUSE";
            $warehouseResult = $conn->query($warehouseQuery);
            while ($row = $warehouseResult->fetch_assoc()) {
                $warehouseRetailOptions[] = $row;
            }
        } elseif ($selectedSupplyType === "RETAIL_SUPPLY") {
            $retailerQuery = "SELECT retailerID AS id, name FROM RETAILER";
            $retailerResult = $conn->query($retailerQuery);
            while ($row = $retailerResult->fetch_assoc()) {
                $warehouseRetailOptions[] = $row;
            }
        }
        ?>

        <form method="POST" action="">
            <h3><?= $editMode ? "Edit" : "Add New" ?> Supply Record</h3>

            <!-- Supply Type -->
            <label for="tableType">Supply Type:</label>
            <select id="tableType" name="tableType" onchange="this.form.submit()" required>
                <option value="">Select Type</option>
                <option value="STORAGE_SUPPLY" <?= $selectedSupplyType === 'STORAGE_SUPPLY' ? 'selected' : '' ?>>Storage Supply</option>
                <option value="RETAIL_SUPPLY" <?= $selectedSupplyType === 'RETAIL_SUPPLY' ? 'selected' : '' ?>>Retail Supply</option>
            </select><br><br>

            <!-- Warehouse/Retailer ID -->
            <label for="warehouseRetailID">
                <?= $selectedSupplyType === "STORAGE_SUPPLY" ? "Warehouse" : ($selectedSupplyType === "RETAIL_SUPPLY" ? "Retailer" : "Warehouse/Retailer") ?> ID:
            </label>
            <select id="warehouseRetailID" name="warehouseRetailID" required>
                <option value="">Select</option>
                <?php foreach ($warehouseRetailOptions as $option): ?>
                    <option value="<?= $option['id'] ?>" 
                        <?= $editMode && 
                           (($selectedSupplyType === "STORAGE_SUPPLY" && $editData['warehouseID'] == $option['id']) || 
                            ($selectedSupplyType === "RETAIL_SUPPLY" && $editData['retailerID'] == $option['id'])) ? 'selected' : '' ?>>
                        <?= $option['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>


            <!-- Product -->
            <label for="productID">Product:</label>
            <select id="productID" name="productID" required>
                <option value="">Select Product</option>
                <?php
                $productQuery = "SELECT productID, productName FROM PRODUCT";
                $productResult = $conn->query($productQuery);
                while ($row = $productResult->fetch_assoc()): ?>
                    <option value="<?= $row['productID'] ?>" <?= $editMode && $editData['productID'] == $row['productID'] ? 'selected' : '' ?>>
                        <?= $row['productName'] ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <!-- Quantity, Unit Price, Total Cost -->
            <label for="quantity">Quantity (tons):</label>
            <input type="number" step="0.01" id="quantity" name="quantity" value="<?= $editMode ? $editData['quantity'] : '' ?>" required><br><br>

            <label for="unitPrice">Unit Price:</label>
            <input type="number" step="0.01" id="unitPrice" name="unitPrice" value="<?= $editMode && isset($editData['unitPrice']) ? $editData['unitPrice'] : '' ?>"><br><br>

            <label for="totalCost">Total Cost:</label>
            <input type="number" step="0.01" id="totalCost" name="totalCost" value="<?= $editMode && isset($editData['totalCost']) ? $editData['totalCost'] : '' ?>"><br><br>

            <!-- Transport Details (Only for Storage Supply) -->

            <?php if ($selectedSupplyType === "STORAGE_SUPPLY"): ?>
                <label for="transportDetails">TransportDetails:</label>
                <select id="transportDetails" name="transportDetails" required>
                    <option value="">Select Transport</option>
                    <option value="Truck 1" <?= $editMode && $editData['transportDetails'] === 'Truck 1' ? 'selected' : '' ?>>Truck 1</option>
                    <option value="Truck 2" <?= $editMode && $editData['transportDetails'] === 'Truck 2' ? 'selected' : '' ?>>Truck 2</option>
                    <option value="Truck 3" <?= $editMode && $editData['transportDetails'] === 'Truck 3' ? 'selected' : '' ?>>Truck 3</option>
                    <option value="Truck 4" <?= $editMode && $editData['transportDetails'] === 'Truck 4' ? 'selected' : '' ?>>Truck 4</option>
                </select><br><br>
            <?php endif; ?>

            <input type="hidden" name="editID" value="<?= $editMode ? $editData['supplyID'] ?? $editData['retailSupplyID'] : '' ?>">
            <button type="submit" name="<?= $editMode ? 'updateSupply' : 'addSupply' ?>">
                <?= $editMode ? "Update Supply" : "Add Supply" ?>
            </button>
        </form>

        <?php
        // Handle Add/Update Operations
        if (isset($_POST['addSupply']) || isset($_POST['updateSupply'])) {
            $tableType = $_POST['tableType'];
            $warehouseRetailID = $_POST['warehouseRetailID'];
            $productID = $_POST['productID'];
            $quantity = $_POST['quantity'];
            $transportDetails = $_POST['transportDetails'];
            $unitPrice = $_POST['unitPrice'] ?? NULL;
            $totalCost = $_POST['totalCost'] ?? NULL;

            if (isset($_POST['addSupply'])) {
                if ($tableType == "STORAGE_SUPPLY") {
                    $sql = $sql = "INSERT INTO STORAGE_SUPPLY (warehouseID, productID, quantity, unitPrice, storageSupplyDate, transportDetails) 
                VALUES ('$warehouseRetailID', '$productID', '$quantity', '$unitPrice', NOW(), '$transportDetails')";
                } elseif ($tableType == "RETAIL_SUPPLY") {
                    $sql = "INSERT INTO RETAIL_SUPPLY (retailerID, productID, quantity, unitPrice, totalCost, retailSupplyDate) 
                            VALUES ('$warehouseRetailID', '$productID', '$quantity', '$unitPrice', '$totalCost', NOW())";
                }
            } elseif (isset($_POST['updateSupply'])) {
                $id = $_POST['editID'];
                if ($tableType == "STORAGE_SUPPLY") {
        $sql = "UPDATE STORAGE_SUPPLY SET warehouseID='$warehouseRetailID', productID='$productID', quantity='$quantity', 
                unitPrice='$unitPrice', transportDetails='$transportDetails' WHERE supplyID='$id'";
    } elseif ($tableType == "RETAIL_SUPPLY") {
        $sql = "UPDATE RETAIL_SUPPLY SET retailerID='$warehouseRetailID', productID='$productID', quantity='$quantity', 
                unitPrice='$unitPrice', totalCost='$totalCost' WHERE retailSupplyID='$id'";
    }
            }

            if ($conn->query($sql) === TRUE) {
                echo "<p>Supply record successfully saved!</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        }

        // Handle Delete
        if (isset($_GET['delete']) && isset($_GET['table'])) {
            $id = $_GET['delete'];
            $table = $_GET['table'];
            $idColumn = $table == "STORAGE_SUPPLY" ? "supplyID" : "retailSupplyID";
            $deleteSql = "DELETE FROM $table WHERE $idColumn='$id'";
            if ($conn->query($deleteSql) === TRUE) {
                echo "<p>Record deleted successfully!</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        }

        // Bar Chart Data Preparation
        $supplyData = [
            'Storage Supply' => 0,
            'Retail Supply' => 0,
            'Production Record' => 0
        ];

        $storageTotal = $conn->query("SELECT SUM(quantity) AS total FROM STORAGE_SUPPLY")->fetch_assoc()['total'];
        $retailTotal = $conn->query("SELECT SUM(quantity) AS total FROM RETAIL_SUPPLY")->fetch_assoc()['total'];
        $productionTotal = $conn->query("SELECT SUM(yield) AS total FROM PRODUCTION_RECORD")->fetch_assoc()['total'];

        $supplyData['Storage Supply'] = $storageTotal ?: 0;
        $supplyData['Retail Supply'] = $retailTotal ?: 0;
        $supplyData['Production Record'] = $productionTotal ?: 0;
        ?>

        <!-- Display Storage Supply Records -->
        <h2>Storage Supply Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Supply ID</th>
                    <th>Warehouse</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $storageSql = "SELECT S.supplyID, W.location AS warehouse, P.productName, S.quantity, S.unitPrice, S.storageSupplyDate 
                               FROM STORAGE_SUPPLY S
                               INNER JOIN WAREHOUSE W ON S.warehouseID = W.warehouseID
                               INNER JOIN PRODUCT P ON S.productID = P.productID";
                $storageResult = $conn->query($storageSql);
                while ($row = $storageResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['supplyID']) ?></td>
                    <td><?= htmlspecialchars($row['warehouse']) ?></td>
                    <td><?= htmlspecialchars($row['productName']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['unitPrice']) ?></td>
                    <td><?= htmlspecialchars($row['storageSupplyDate']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['supplyID'] ?>&table=STORAGE_SUPPLY"><button>Edit</button></a>
                        <a href="?delete=<?= $row['supplyID'] ?>&table=STORAGE_SUPPLY" onclick="return confirm('Are you sure you want to delete this record?');"><button>Delete</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Display Retail Supply Records -->
        <h2>Retail Supply Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Retail Supply ID</th>
                    <th>Retailer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Cost</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $retailSql = "SELECT R.retailSupplyID, T.name AS retailer, P.productName, R.quantity, R.unitPrice, R.totalCost, R.retailSupplyDate 
                              FROM RETAIL_SUPPLY R
                              INNER JOIN RETAILER T ON R.retailerID = T.retailerID
                              INNER JOIN PRODUCT P ON R.productID = P.productID";
                $retailResult = $conn->query($retailSql);
                while ($row = $retailResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['retailSupplyID']) ?></td>
                    <td><?= htmlspecialchars($row['retailer']) ?></td>
                    <td><?= htmlspecialchars($row['productName']) ?></td>
                    <td><?= htmlspecialchars($row['quantity']) ?></td>
                    <td><?= htmlspecialchars($row['unitPrice']) ?></td>
                    <td><?= htmlspecialchars($row['totalCost']) ?></td>
                    <td><?= htmlspecialchars($row['retailSupplyDate']) ?></td>
                    <td>
                        <a href="?edit=<?= $row['retailSupplyID'] ?>&table=RETAIL_SUPPLY"><button>Edit</button></a>
                        <a href="?delete=<?= $row['retailSupplyID'] ?>&table=RETAIL_SUPPLY" onclick="return confirm('Are you sure you want to delete this record?');"><button>Delete</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Bar Chart -->
        <h2>Current Supply Levels</h2>
        <canvas id="supplyChart"></canvas>
        <script>
            const ctx = document.getElementById('supplyChart').getContext('2d');
            const data = {
                labels: <?= json_encode(array_keys($supplyData)) ?>,
                datasets: [{
                    label: 'Quantity (tons)',
                    data: <?= json_encode(array_values($supplyData)) ?>,
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56']
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Current Supply Levels' }
                    }
                }
            };

            new Chart(ctx, config);
        </script>
    </section>
    </main>
</body>
</html>
