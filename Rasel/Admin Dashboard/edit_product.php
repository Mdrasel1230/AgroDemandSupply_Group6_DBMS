<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

include 'db_connect.php';

$productID = $_GET['productID'] ?? null;

if (!$productID) {
    header("Location: product_info.php");
    exit();
}

$sql = "SELECT * FROM PRODUCT WHERE productID='$productID'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: product_info.php");
    exit();
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>Edit Product</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Edit Product</h1>

        <form method="POST" action="">
            <input type="hidden" name="productID" value="<?= $product['productID'] ?>">

            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" value="<?= htmlspecialchars($product['productName']) ?>" required><br><br>

            <label for="productType">Product Type:</label>
            <input type="text" id="productType" name="productType" value="<?= htmlspecialchars($product['type']) ?>" required><br><br>

            <label for="variety">Variety:</label>
            <input type="text" id="variety" name="variety" value="<?= htmlspecialchars($product['variety']) ?>"><br><br>

            <label for="seasonality">Seasonality:</label>
            <input type="text" id="seasonality" name="seasonality" value="<?= htmlspecialchars($product['seasonality']) ?>"><br><br>

            <button type="submit">Update Product</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productID = $_POST['productID'];
            $productName = $_POST['productName'];
            $productType = $_POST['productType'];
            $variety = $_POST['variety'];
            $seasonality = $_POST['seasonality'];

            $sql = "UPDATE PRODUCT SET productName='$productName', type='$productType', variety='$variety', seasonality='$seasonality' WHERE productID='$productID'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Product updated successfully!</p>";
                header("Location: product_info.php"); // Redirect to product_info.php
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