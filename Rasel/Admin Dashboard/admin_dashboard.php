<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="product_info.php">Product Information</a></li>
            <li><a href="production_data.php">Historical Production Data</a></li>
            <li><a href="real_time_supply.php">Real-Time Supply Levels</a></li>
            <li><a href="warehouse_supply.php">Warehouse Supply Trend</a></li>
            <li><a href="market_price.php">Market Price Data</a></li>
            <li><a href="demand_analysis.php">Consumer Demand Data</a></li>
            <li><a href="user_management.php">User Management</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Welcome to the Admin Dashboard</h2>
            <p>This dashboard provides an overview of agricultural data and insights.</p>

            <!-- Charts Section -->
            <div style="width: 100%; max-width: 800px; margin: 0 auto;">
                <!-- Consumer Pattern -->
                <h3>Consumer Pattern</h3>
                <canvas id="consumerPatternChart"></canvas>
                <?php
                $consumerPatternSql = "
                    SELECT p.productName, da.consumptionPattern
                    FROM DEMAND_ANALYSIS da
                    JOIN PRODUCT p ON da.productID = p.productID
                ";
                $consumerPatternResult = $conn->query($consumerPatternSql);
                $consumerPatternData = [];
                while ($row = $consumerPatternResult->fetch_assoc()) {
                    $consumerPatternData[$row['productName']] = $row['consumptionPattern'];
                }
                ?>

                <!-- Price Elasticity -->
                <h3>Price Elasticity</h3>
                <canvas id="priceElasticityChart"></canvas>
                <?php
                $priceElasticitySql = "
                    SELECT p.productName, da.priceElasticity
                    FROM DEMAND_ANALYSIS da
                    JOIN PRODUCT p ON da.productID = p.productID
                ";
                $priceElasticityResult = $conn->query($priceElasticitySql);
                $priceElasticityData = [];
                while ($row = $priceElasticityResult->fetch_assoc()) {
                    $priceElasticityData[$row['productName']] = $row['priceElasticity'];
                }
                ?>

                <!-- Supply Trend -->
                <h3>Supply Trend (Region Wise)</h3>
                <canvas id="supplyTrendChart"></canvas>
                <?php
                $supplyTrendSql = "
                    SELECT ad.region, SUM(ad.quantitySupplied) AS totalSupplied
                    FROM ANALYTICS_DATA ad
                    GROUP BY ad.region
                ";
                $supplyTrendResult = $conn->query($supplyTrendSql);
                $supplyTrendData = [];
                while ($row = $supplyTrendResult->fetch_assoc()) {
                    $supplyTrendData[$row['region']] = $row['totalSupplied'];
                }
                ?>

                <!-- Yield vs Acreages -->
                <h3>Yield vs Acreages</h3>
                <canvas id="yieldAcreagesChart"></canvas>
                <?php
                $yieldAcreagesSql = "
                    SELECT p.productName, pr.yield, pr.acreage
                    FROM PRODUCTION_RECORD pr
                    JOIN PRODUCT p ON pr.productID = p.productID
                ";
                $yieldAcreagesResult = $conn->query($yieldAcreagesSql);
                $yieldAcreagesData = [];
                while ($row = $yieldAcreagesResult->fetch_assoc()) {
                    $yieldAcreagesData[] = [
                        'product' => $row['productName'],
                        'yield' => $row['yield'],
                        'acreage' => $row['acreage'],
                    ];
                }
                ?>
            </div>
        </section>
    </main>

    <!-- Chart.js Scripts -->
    <script>
        // Consumer Pattern Chart
        const consumerPatternData = <?= json_encode($consumerPatternData) ?>;
        const consumerPatternLabels = Object.keys(consumerPatternData);
        const consumerPatternValues = Object.values(consumerPatternData);

        new Chart(document.getElementById('consumerPatternChart'), {
            type: 'pie',
            data: {
                labels: consumerPatternLabels,
                datasets: [{
                    data: consumerPatternValues.map(() => 1), // Equal weight for each pattern
                    backgroundColor: consumerPatternLabels.map(() =>
                        `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`
                    ),
                }],
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return consumerPatternLabels[context.dataIndex] + ': ' + consumerPatternValues[context.dataIndex];
                            },
                        },
                    },
                    title: {
                        display: true,
                        text: 'Consumer Pattern',
                    },
                },
            },
        });

        // Price Elasticity Chart
        const priceElasticityData = <?= json_encode($priceElasticityData) ?>;
        new Chart(document.getElementById('priceElasticityChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(priceElasticityData),
                datasets: [{
                    label: 'Price Elasticity',
                    data: Object.values(priceElasticityData),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Price Elasticity by Product',
                    },
                },
            },
        });

        // Supply Trend Chart
        const supplyTrendData = <?= json_encode($supplyTrendData) ?>;
        new Chart(document.getElementById('supplyTrendChart'), {
            type: 'pie',
            data: {
                labels: Object.keys(supplyTrendData),
                datasets: [{
                    data: Object.values(supplyTrendData),
                    backgroundColor: Object.keys(supplyTrendData).map(() =>
                        `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`
                    ),
                }],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Supply Trend (Region Wise)',
                    },
                },
            },
        });

        // Yield vs Acreages Chart
        const yieldAcreagesData = <?= json_encode($yieldAcreagesData) ?>;
        const yieldLabels = yieldAcreagesData.map(d => d.product);
        const yieldValues = yieldAcreagesData.map(d => d.yield);
        const acreageValues = yieldAcreagesData.map(d => d.acreage);

        new Chart(document.getElementById('yieldAcreagesChart'), {
            type: 'bar',
            data: {
                labels: yieldLabels,
                datasets: [
                    {
                        label: 'Yield',
                        data: yieldValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                    },
                    {
                        label: 'Acreages',
                        data: acreageValues,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Yield vs Acreages by Product',
                    },
                },
            },
        });
    </script>
</body>
</html>