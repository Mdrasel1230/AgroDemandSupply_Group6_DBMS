<?php
// Step 1: Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Update your database name here

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Fetch data for the chart (e.g., category-wise sales data)
$sql_sales = "SELECT category, SUM(sales) AS total_sales FROM products GROUP BY category";
$sales_result = $conn->query($sql_sales);

// Prepare chart data for JavaScript
$categories = [];
$sales = [];
while ($row = $sales_result->fetch_assoc()) {
    $categories[] = $row['category'];
    $sales[] = $row['total_sales'];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumer Dashboard</title>
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include your styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 10px;
            background-color: #333;
            color: white;
        }
        .category-card {
            width: 30%;
            display: inline-block;
            margin-right: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .category-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .category-card h3 {
            margin-top: 15px;
        }
        .category-card p {
            color: #555;
        }
        .category-card a {
            margin-top: 10px;
            display: inline-block;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .category-card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Main Container -->
<div class="container">
    <h1>Consumer Dashboard</h1>
    
    <!-- Step 3: Display Categories -->
    <section>
        <h2>Shop By Category</h2>
        <div class="category-card">
            <img src="grains.jpg" alt="Grains">
            <h3>Grains</h3>
            <p>Find high-quality grains at the best price.</p>
            <a href="#">Explore</a>
        </div>
        <div class="category-card">
            <img src="fruits.jpg" alt="Fruits">
            <h3>Fruits</h3>
            <p>Get fresh fruits directly from farms.</p>
            <a href="#">Explore</a>
        </div>
        <div class="category-card">
            <img src="vegetables.jpg" alt="Vegetables">
            <h3>Vegetables</h3>
            <p>Fresh vegetables sourced locally.</p>
            <a href="#">Explore</a>
        </div>
    </section>
    
    <!-- Step 4: Sales Chart -->
    <section>
        <h2>Category-wise Sales</h2>
        <canvas id="salesChart"></canvas>
        <script>
            // Step 5: Generate the Chart using Chart.js
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar', // Bar chart
                data: {
                    labels: <?php echo json_encode($categories); ?>, // PHP data passed to JavaScript
                    datasets: [{
                        label: 'Sales in Each Category',
                        data: <?php echo json_encode($sales); ?>, // PHP data passed to JavaScript
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </section>
    
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 For Better Agriculture. All Rights Reserved.</p>
</footer>

</body>
</html>
