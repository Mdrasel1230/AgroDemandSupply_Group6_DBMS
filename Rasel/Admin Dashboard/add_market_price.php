<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Market Price</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>Add Market Price</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Add Market Price</h1>

        <?php
        include 'db_connect.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productID = $_POST['productID'];
            $price = $_POST['price'];
            $date = $_POST['date'];

            $sql = "INSERT INTO market_prices (productID, price, date) VALUES ('$productID', '$price', '$date')";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Market price added successfully!</p>";
                header("Location: market_price.php");
                exit();
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        }

        $productQuery = "SELECT * FROM PRODUCT";
        $productResult = $conn->query($productQuery);
        ?>

        <form method="POST" action="">
            <label for="productID">Product:</label>
            <select id="productID" name="productID" required>
                <option value="">Select Product</option>
                <?php while ($product = $productResult->fetch_assoc()): ?>
                    <option value="<?= $product['productID'] ?>"><?= htmlspecialchars($product['productName']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label for="price">Price (USD):</label>
            <input type="number" step="0.01" id="price" name="price" required><br><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br><br>

            <button type="submit">Add Price</button>
        </form>
    </section>
    </main>
</body>
</html>
