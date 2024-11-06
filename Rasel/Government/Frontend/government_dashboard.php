<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Government Dashboard</title>
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
        <h1>Government Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="market_regulation.php">Market Regulation</a></li>
            <li><a href="subsidy_management.php">Subsidy Management</a></li>
            <li><a href="production_reports.php">Production Reports</a></li>
            <li><a href="forecasting.php">Forecasting</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>Welcome to the Government Dashboard</h2>
            <p>Manage market regulations, subsidies, and production reports here.</p>
        </section>
    </main>
</body>
</html>
