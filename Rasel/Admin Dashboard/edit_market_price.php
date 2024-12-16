<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$priceID = $_GET['priceID'] ?? null;

if (!$priceID) {
    header("Location: market_price.php");
    exit();
}

$sql = "SELECT * FROM market_prices WHERE priceID='$priceID'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: market_price.php");
    exit();
}

$priceData = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Market Price</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>Edit Market Price</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Edit Market Price</h1>

        <?php
        $productQuery = "SELECT * FROM PRODUCT";
        $productResult = $conn->query($productQuery);
        ?>

        <form method="POST" action="">
            <input type="hidden" name="priceID" value="<?= $priceData['priceID'] ?>">

            <label for="productID">Product:</label>
            <select id="productID" name="productID" required>
                <?php while ($product = $productResult->fetch_assoc()): ?>
                    <option value="<?= $product['productID'] ?>" <?= $priceData['productID'] == $product['productID'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($product['productName']) ?>
                    </option>
                <?php endwhile; ?>
            </select><br><br>

            <label for="price">Price (USD):</label>
            <input type="number" step="0.01" id="price" name="price" value="<?= htmlspecialchars($priceData['price']) ?>" required><br><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($priceData['date']) ?>" required><br><br>

            <button type="submit" name="updatePrice">Update Price</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $priceID = $_POST['priceID'];
            $productID = $_POST['productID'];
            $price = $_POST['price'];
            $date = $_POST['date'];

            $sql = "UPDATE market_prices SET productID='$productID', price='$price', date='$date' WHERE priceID='$priceID'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Market price updated successfully!</p>";
                header("Location: market_price.php");
                exit();
            } else {
                echo "<p>Error: " . $conn->error . "</p>";
            }
        }
        ?>
    </section>
    </main>
</body>
</html>