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
    <title>Add Product</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>Add Product</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Add Product</h1>

        <form method="POST" action="">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" required><br><br>

            <label for="productType">Product Type:</label>
            <input type="text" id="productType" name="productType" required><br><br>
            
            <label for="variety">Variety:</label>
            <input type="text" id="variety" name="variety"><br><br>
            
            <label for="seasonality">Seasonality:</label>
            <input type="text" id="seasonality" name="seasonality"><br><br>
            
            <button type="submit">Add Product</button>
        </form>

        <?php
        include 'db_connect.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $productName = $_POST['productName'];
            $productType = $_POST['productType'];
            $variety = $_POST['variety'];
            $seasonality = $_POST['seasonality'];

            $sql = "INSERT INTO PRODUCT (productName, type, variety, seasonality) 
                    VALUES ('$productName', '$productType', '$variety', '$seasonality')";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Product added successfully!</p>";
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