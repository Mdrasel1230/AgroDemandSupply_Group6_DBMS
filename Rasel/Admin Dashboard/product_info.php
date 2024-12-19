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
    <title>Product Information</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>Product Information</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <main>
    <section>
        <h1>Product Information</h1>

        <!-- Search Form -->
        <form method="GET" action="" style="margin-bottom: 20px;">
            <label for="search">Search Products:</label>
            <input type="text" id="search" name="search" placeholder="Enter product name or type">
            <button type="submit">Search</button>
            <a href="add_product.php"><button type="button">Add New Product</button></a>
        </form>

        <?php
        include 'db_connect.php';

        $searchQuery = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = $conn->real_escape_string($_GET['search']);
            $searchQuery = "WHERE productName LIKE '%$searchTerm%' OR type LIKE '%$searchTerm%'";
        }

        $sql = "SELECT * FROM PRODUCT $searchQuery";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
        ?>
        <h2>Existing Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Type</th>
                    <th>Variety</th>
                    <th>Seasonality</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['productID']) ?></td>
                    <td><?= htmlspecialchars($row['productName']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['variety']) ?></td>
                    <td><?= htmlspecialchars($row['seasonality']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="edit_product.php?productID=<?= $row['productID'] ?>"><button type="button">Edit</button></a>
                        <!-- Delete Button -->
                        <a href="?delete=<?= $row['productID'] ?>" onclick="return confirm('Are you sure you want to delete this product?');">
                            <button type="button">Delete</button>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

        <?php
        if (isset($_GET['delete'])) {
            $productID = $_GET['delete'];
            $sql = "DELETE FROM PRODUCT WHERE productID='$productID'";
            if ($conn->query($sql) === TRUE) {
                echo "<p>Product deleted successfully!</p>";
                header("Location: product_info.php"); // Redirect to refresh
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
