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
    <title>Warehouse Supply Distribution</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Warehouse Supply Distribution</h1>
    </header>
    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Warehouse Supply Distribution</h1>
        <?php
        include 'db_connect.php';

        // Fetch total supply per region
        $sql = "SELECT W.zone AS region, SUM(S.quantity) AS total_supply
                FROM STORAGE_SUPPLY S
                INNER JOIN WAREHOUSE W ON S.warehouseID = W.warehouseID
                GROUP BY W.zone";
        $result = $conn->query($sql);

        $regions = [];
        $supplies = [];
        while ($row = $result->fetch_assoc()) {
            $regions[] = $row['region'];
            $supplies[] = $row['total_supply'];
        }
        ?>

        <canvas id="supplyDistributionChart"></canvas>
        <script>
            const ctx = document.getElementById('supplyDistributionChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode($regions) ?>,
                    datasets: [{
                        data: <?= json_encode($supplies) ?>,
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ],
                        hoverOffset: 4,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: 'Warehouse Supply Distribution by Region' },
                    },
                },
            });
        </script>
    </section>
    </main>
</body>
</html>
