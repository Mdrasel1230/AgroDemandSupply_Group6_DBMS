<!DOCTYPE html>
<html lang="en">
<head>
    <title>Demand Analysis</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Demand Analysis</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Consumer Demand Data</h1>

        <?php
        include 'db_connect.php';

        // Fetch Price Elasticity Data
        $elasticitySql = "
            SELECT 
                p.productName,
                (SUM(ss.quantity) / NULLIF(MAX(ph.perUnitPriceValue) - MIN(ph.perUnitPriceValue), 0)) AS elasticity
            FROM STORAGE_SUPPLY ss
            JOIN PRICE_HISTORY ph ON ss.productID = ph.productID
            JOIN PRODUCT p ON ss.productID = p.productID
            WHERE ph.date BETWEEN (CURDATE() - INTERVAL 5 YEAR) AND CURDATE()
            GROUP BY p.productName;
        ";
        $elasticityResult = $conn->query($elasticitySql);
        $elasticityData = [];
        while ($row = $elasticityResult->fetch_assoc()) {
            $elasticityData[] = [
                'productName' => $row['productName'],
                'elasticity' => $row['elasticity'] ?: 0, // Handle NULL or 0
            ];
        }

        // Fetch Supply Quantity Over Time
        $supplyQuantitySql = "
            SELECT 
                p.productName,
                DATE_FORMAT(ss.storageSupplyDate, '%Y-%m') AS month,
                SUM(ss.quantity) AS totalQuantity
            FROM STORAGE_SUPPLY ss
            JOIN PRODUCT p ON ss.productID = p.productID
            WHERE ss.storageSupplyDate BETWEEN (CURDATE() - INTERVAL 5 YEAR) AND CURDATE()
            GROUP BY p.productName, DATE_FORMAT(ss.storageSupplyDate, '%Y-%m')
            ORDER BY p.productName, DATE_FORMAT(ss.storageSupplyDate, '%Y-%m') ASC;
        ";
        $supplyQuantityResult = $conn->query($supplyQuantitySql);

        // Group data for row chart
        $groupedSupplyData = [];
        while ($row = $supplyQuantityResult->fetch_assoc()) {
            $productName = $row['productName'];
            $month = $row['month'];
            $quantity = $row['totalQuantity'];

            if (!isset($groupedSupplyData[$productName])) {
                $groupedSupplyData[$productName] = [];
            }
            $groupedSupplyData[$productName][] = [
                'month' => $month,
                'quantity' => $quantity,
            ];
        }

        // Fetch Product Deficits
        $deficitSql = "
            SELECT p.productName, ad.region, (ad.quantityDemanded - ad.quantitySupplied) AS deficit
            FROM ANALYTICS_DATA ad
            JOIN PRODUCT p ON ad.productID = p.productID
            WHERE ad.quantityDemanded > ad.quantitySupplied;
        ";
        $deficitResult = $conn->query($deficitSql);
        $deficitData = [];
        while ($row = $deficitResult->fetch_assoc()) {
            $deficitData[] = [
                'productName' => $row['productName'],
                'region' => $row['region'],
                'deficit' => $row['deficit'],
            ];
        }
        ?>

        <!-- Price Elasticity Chart -->
        <h2>Price Elasticity by Product</h2>
        <canvas id="elasticityChart"></canvas>
        <script>
            const elasticityData = <?= json_encode($elasticityData) ?>;
            const elasticityLabels = elasticityData.map(item => item.productName);
            const elasticityValues = elasticityData.map(item => item.elasticity);

            new Chart(document.getElementById('elasticityChart'), {
                type: 'bar',
                data: {
                    labels: elasticityLabels,
                    datasets: [{
                        label: 'Price Elasticity',
                        data: elasticityValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Price Elasticity by Product (Last 5 Years)' },
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Elasticity' } },
                    },
                },
            });
        </script>

<?php

        ?>

        <!-- Supply Quantity Over Time Row Chart -->
        <h2>Supply Quantity Over Time</h2>
        <canvas id="supplyRowChart"></canvas>
        <script>
    const groupedSupplyData = <?= json_encode($groupedSupplyData) ?>;

    // Prepare datasets and labels with dates
    const supplyLabels = Object.keys(groupedSupplyData);
    const supplyQuantities = [];
    const supplyTooltips = []; // For hover tooltips

    supplyLabels.forEach(productName => {
        const productData = groupedSupplyData[productName];
        const totalQuantity = productData.reduce((sum, entry) => sum + entry.quantity, 0); // Total quantity
        supplyQuantities.push(totalQuantity);

        // Generate tooltip and label with dates
        const tooltipData = productData.map(entry => `${entry.month}: ${entry.quantity} tons`);
        const labelWithDates = `${productName} (${tooltipData.join(", ")})`;

        supplyTooltips.push(tooltipData.join("\n")); // Tooltips for hover
        supplyLabels[supplyLabels.indexOf(productName)] = labelWithDates; // Update labels with dates
    });

    // Render Row Chart
    new Chart(document.getElementById('supplyRowChart'), {
        type: 'bar',
        data: {
            labels: supplyLabels,
            datasets: [{
                label: 'Total Supply Quantity (tons)',
                data: supplyQuantities,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            indexAxis: 'y', // Horizontal Bar Chart
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return supplyTooltips[context.dataIndex]; // Show detailed tooltip with dates and quantities
                        },
                    },
                },
                title: {
                    display: true,
                    text: 'Supply Quantity Over Time (Last 5 Years)',
                },
            },
            scales: {
                x: { beginAtZero: true, title: { display: true, text: 'Quantity (tons)' } },
                y: { title: { display: true, text: 'Products' } },
            },
        },
    });        
</script>
        <!-- Product Deficit Chart -->
        <h2>Product Deficits by Region</h2>
        <canvas id="deficitChart"></canvas>
        <script>
            const deficitData = <?= json_encode($deficitData) ?>;
            const deficitLabels = deficitData.map(item => `${item.productName} (${item.region})`);
            const deficitValues = deficitData.map(item => item.deficit);

            new Chart(document.getElementById('deficitChart'), {
                type: 'bar',
                data: {
                    labels: deficitLabels,
                    datasets: [{
                        label: 'Deficit (Quantity)',
                        data: deficitValues,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Product Deficits by Region' },
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Deficit (tons)' } },
                    },
                },
            });
        </script>

    </section>
    </main>
</body>
</html>
