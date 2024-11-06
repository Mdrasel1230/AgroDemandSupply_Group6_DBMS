<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumer Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<style>
/* Inline CSS to apply the background image to the entire body */
body {
    background-image: url('css/bg_picture.jpg');  /* Adjust the path if needed */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #333;
    min-height: 100vh;
    margin: 0;
    font-family: Arial, sans-serif;
}



nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6); /* Dark background for the nav */
    border-radius: 8px;
}

nav ul li {
    margin: 0 10px;
}

nav ul li a {
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
    font-weight: bold;
}

nav ul li a:hover {
    background-color: rgba(255, 255, 255, 0.3);
}
</style>
    <header>
        <h1>Consumer Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="product_info.php">Product Information</a></li>
            <li><a href="demand_analysis.php">Demand Analysis</a></li>
            <li><a href="purchase_history.php">Purchase History</a></li>
            <li><a href="price_trends.php">Price Trends</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Welcome to the Consumer Dashboard</h2>
            <p>Here you can view product information, analyze demand, and check price trends.</p>
        </section>
    </main>
</body>
</html>
