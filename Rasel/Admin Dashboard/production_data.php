<!DOCTYPE html>
<html lang="en">
<head>
    <title>Historical Production Data</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Historical Production Data</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Historical Production Data</h1>

        <!-- Add New Record Form -->
        <h3>Add New Record</h3>
        <form method="POST" action="">
            <!-- Product Dropdown -->
            <label for="productID">Product:</label>
            <select id="productID" name="productID" required>
                <option value="">Select Product</option>
                <?php
                include 'db_connect.php';
                // Fetch products from the PRODUCT table
                $sql = "SELECT productID, productName FROM PRODUCT";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['productID'] . "'>" . $row['productName'] . "</option>";
                }
                ?>
            </select><br><br>

            <!-- Region Dropdown -->
            <label for="region">Region:</label>
            <select id="region" name="region" required>
                <option value="">Select Region</option>
                <option value="Dhaka">Dhaka</option>
                <option value="Barishal">Barishal</option>
                <option value="Chattogram">Chattogram</option>
                <option value="Khulna">Khulna</option>
                <option value="Rajshahi">Rajshahi</option>
                <option value="Rangpur">Rangpur</option>
                <option value="Mymensingh">Mymensingh</option>
                <option value="Sylhet">Sylhet</option>
            </select><br><br>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" required><br><br>

            <label for="yield">Yield (tons):</label>
            <input type="number" step="0.01" id="yield" name="yield" required><br><br>

            <label for="acreage">Acreage (hectares):</label>
            <input type="number" step="0.01" id="acreage" name="acreage" required><br><br>

            <label for="costOfProduction">Cost of Production:</label>
            <input type="number" step="0.01" id="costOfProduction" name="costOfProduction" required><br><br>

            <button type="submit" name="addRecord">Add Record</button>
        </form>

        <?php
        // Handle form submission for adding a new record
        if (isset($_POST['addRecord'])) {
            $productID = $_POST['productID'];
            $region = $_POST['region'];
            $year = $_POST['year'];
            $yield = $_POST['yield'];
            $acreage = $_POST['acreage'];
            $costOfProduction = $_POST['costOfProduction'];

            // Insert into the PRODUCTION_RECORD table
            $sql = "INSERT INTO PRODUCTION_RECORD (productID, region, year, yield, acreage, costOfProduction) 
                    VALUES ('$productID', '$region', '$year', '$yield', '$acreage', '$costOfProduction')";
            if ($conn->query($sql) === TRUE) {
                echo "<p>New record added successfully!</p>";
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        }

        // Fetch data from the PRODUCTION_RECORD table
        $sql = "SELECT pr.recordID, p.productName, pr.region, pr.year, pr.yield, pr.acreage, pr.costOfProduction 
                FROM PRODUCTION_RECORD pr
                JOIN PRODUCT p ON pr.productID = p.productID";
        $result = $conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Prepare data for the chart
        $years = array_column($data, 'year');
        $yields = array_column($data, 'yield');
        $acreages = array_column($data, 'acreage');
        $costOfProduction = array_column($data, 'costOfProduction');
        ?>

        <!-- Render the chart -->
        <canvas id="yieldAcreageChart"></canvas>
        <script>
            const ctx = document.getElementById('yieldAcreageChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', // Line chart for year-wise comparison
                data: {
                    labels: <?= json_encode($years) ?>,
                    datasets: [
                        {
                            label: 'Yield (tons)',
                            data: <?= json_encode($yields) ?>,
                            borderColor: '#FF6384',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Acreages (hectares)',
                            data: <?= json_encode($acreages) ?>,
                            borderColor: '#36A2EB',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                        },
                        {
                            label: 'Cost of Production',
                            data: <?= json_encode($costOfProduction) ?>,
                            borderColor: '#FFCE56',
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Yield vs. Acreages and Cost of Production Over the Years' },
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Year',
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Value',
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });
        </script>

        <?php
        // Handle form submission for Create or Update
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $year = $_POST['year'];
            $yield = $_POST['yield'];
            $acreage = $_POST['acreage'];
            $costOfProduction = $_POST['costOfProduction'];
            if (isset($_POST['recordID'])) {
                $recordID = $_POST['recordID'];
                $sql = "UPDATE PRODUCTION_RECORD SET year='$year', yield='$yield', acreage='$acreage', costOfProduction='$costOfProduction' WHERE recordID='$recordID'";
                $conn->query($sql);
                echo "<p>Record updated successfully!</p>";
            }
        }

        // Handle Delete
        if (isset($_GET['delete'])) {
            $recordID = $_GET['delete'];
            $sql = "DELETE FROM PRODUCTION_RECORD WHERE recordID='$recordID'";
            $conn->query($sql);
            echo "<p>Record deleted successfully!</p>";
        }

        // Retrieve all records
        $sql = "SELECT recordID, year, yield, acreage, costOfProduction FROM PRODUCTION_RECORD ORDER BY year";
        $result = $conn->query($sql);
        ?>

        <h2>Existing Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Yield</th>
                    <th>Acreage</th>
                    <th>Cost of Production</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['year']) ?></td>
                    <td><?= htmlspecialchars($row['yield']) ?></td>
                    <td><?= htmlspecialchars($row['acreage']) ?></td>
                    <td><?= htmlspecialchars($row['costOfProduction']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="?edit=<?= $row['recordID'] ?>">
                            <button>Edit</button>
                        </a>
                        <!-- Delete Button -->
                        <a href="?delete=<?= $row['recordID'] ?>" onclick="return confirm('Are you sure you want to delete this record?');">
                            <button>Delete</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        // Edit functionality
        if (isset($_GET['edit'])) {
            $recordID = $_GET['edit'];
            $sql = "SELECT * FROM PRODUCTION_RECORD WHERE recordID='$recordID'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
        ?>
        <!-- Display form with pre-filled values for editing -->
        <h3>Edit Record</h3>
        <form method="POST" action="">
            <input type="hidden" name="recordID" value="<?= $row['recordID'] ?>">

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($row['year']) ?>" required><br><br>

            <label for="yield">Yield (tons):</label>
            <input type="number" step="0.01" id="yield" name="yield" value="<?= htmlspecialchars($row['yield']) ?>" required><br><br>

            <label for="acreage">Acreage (hectares):</label>
            <input type="number" step="0.01" id="acreage" name="acreage" value="<?= htmlspecialchars($row['acreage']) ?>" required><br><br>

            <label for="costOfProduction">Cost of Production:</label>
            <input type="number" step="0.01" id="costOfProduction" name="costOfProduction" value="<?= htmlspecialchars($row['costOfProduction']) ?>" required><br><br>

            <button type="submit">Update Record</button>
        </form>
        <?php } ?>

    </section>
    </main>
</body>
</html>
