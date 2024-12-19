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
    <title>Market Price Data</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Market Price Data</h1>
    </header>

<?php include 'navbar.html'; ?>
<main>
<section>
    <h1>Market Price Data</h1>

    <!-- Search Form -->
    <form method="GET" action="" style="margin-bottom: 20px;">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Enter product name">
        <button type="submit">Search</button>
        <a href="add_market_price.php"><button type="button">Add New Price</button></a>
    </form>

    <?php
    include 'db_connect.php';

    $searchQuery = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $searchQuery = "WHERE p.productName LIKE '%$searchTerm%'";
    }

    $sql = "
        SELECT mp.priceID, p.productName, mp.price, mp.date 
        FROM market_prices mp
        JOIN PRODUCT p ON mp.productID = p.productID
        $searchQuery
    ";
    $result = $conn->query($sql);
    ?>

    <!-- Display Market Price Records -->
    <h2>Market Price Records</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price (USD)</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['productName']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td>
                        <a href="edit_market_price.php?priceID=<?= $row['priceID'] ?>"><button type="button">Edit</button></a>
                        <a href="?delete=<?= $row['priceID'] ?>" onclick="return confirm('Are you sure you want to delete this record?');"><button type="button">Delete</button></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    if (isset($_GET['delete'])) {
        $priceID = $_GET['delete'];
        $sql = "DELETE FROM market_prices WHERE priceID='$priceID'";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Market price record deleted successfully!</p>";
            header("Location: market_price.php");
            exit();
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    <?php
    // Fetch chart data
    $chartSql = "
        SELECT p.productName, mp.price, mp.date 
        FROM market_prices mp
        JOIN PRODUCT p ON mp.productID = p.productID
        JOIN (
            SELECT productID, date
            FROM (
                SELECT productID, date, ROW_NUMBER() OVER (PARTITION BY productID ORDER BY date DESC) AS row_num
                FROM market_prices
            ) AS ranked
            WHERE row_num <= 2
        ) AS last_two ON mp.productID = last_two.productID AND mp.date = last_two.date
        ORDER BY p.productName, mp.date ASC;
    ";
    $chartResult = $conn->query($chartSql);

    $chartData = [];
    while ($row = $chartResult->fetch_assoc()) {
        $chartData[$row['productName']][] = [
            'date' => $row['date'],
            'price' => $row['price'],
        ];
    }
    ?>
    <!-- Chart -->
    <h2>Market Prices Chart</h2>
    <canvas id="priceChart"></canvas>
    <script>
        const chartData = <?= json_encode($chartData) ?>;
        const labels = [];
        const datasets = [];

        Object.keys(chartData).forEach(product => {
            const productData = chartData[product];
            const productLabels = productData.map(d => d.date);
            const productPrices = productData.map(d => d.price);

            productLabels.forEach(date => {
                if (!labels.includes(date)) labels.push(date);
            });

            datasets.push({
                label: product,
                data: productPrices,
                backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`,
                borderColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 1)`,
                borderWidth: 1,
            });
        });

        new Chart(document.getElementById('priceChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets,
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Price Comparison by Product' },
                },
                scales: {
                    x: { title: { display: true, text: 'Dates' } },
                    y: { title: { display: true, text: 'Price (USD)' }, beginAtZero: true },
                },
            },
        });
    </script>
</section>
</main>
</body>
</html>