<?php
// Database connection //
$conn = new mysqli("localhost", "root", "", "agriculture_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API: Handle AJAX search requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['searchTerm'])) {
        $searchTerm = '%' . $conn->real_escape_string($input['searchTerm']) . '%';

        $query = "SELECT id, name, description, price FROM PRODUCTS WHERE name LIKE ? OR description LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $searchTerm, $searchTerm);

        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
        exit;
    }
    echo json_encode([]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consumer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
    
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Logo and Title -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="https://dynamic.design.com/preview/logodraft/76e83145-8c75-4a6e-9b9b-ecad55c87096/image/large.png" alt="Logo" height="40">
            <span class="ms-2">For Better Agriculture</span>
        </a>

        <!-- Delivery Location -->
        <div class="d-flex align-items-center">
            <label class="me-2" for="locationSelector">Delivery to:</label>
            <select class="form-select form-select-sm me-4" id="locationSelector">
                <option selected>Mohammadpur</option>
                <option value="1">Shuchona</option>
                <option value="2">Society-06</option>
            </select>
        </div>

        <!-- Search Bar -->
        <div class="d-flex align-items-center">
            <input type="text" class="form-control me-2" placeholder="Search for 'products'">
            <button class="btn btn-primary">Search</button>
        </div>

        <!-- User Greeting and Links -->
        <div class="d-flex align-items-center">
            <span class="me-3">Hello, Mubasshiraaa</span>
            <a class="nav-link me-2" href="feedback.php">Feedback</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-4 upper-section">
    <h1 class="text-center mb-4">Agricultural Dashboard</h1>
    <div class="row">
      <!-- Cards for Summary -->
      <div class="container mt-4">
        <!-- Row 1 -->
        <div class="row mb-3">
          <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
              <div class="card-body">
                <h5 class="card-title">Total Inventory</h5>
                <p class="card-text" id="totalInventory">10,000 Units</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-white bg-success h-100">
              <div class="card-body">
                <h5 class="card-title">Active Warehouses</h5>
                <p class="card-text" id="activeWarehouses">12</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-white bg-danger h-100">
              <div class="card-body">
                <h5 class="card-title">Total Products Available</h5>
                <p class="card-text" id="totalProducts">45</p>
              </div>
            </div>
          </div>
        </div>
      
        <!-- Row 2 -->
        <div class="row">
          <div class="col-md-4">
            <div class="card text-white bg-secondary h-100">
              <div class="card-body">
                <h5 class="card-title">Product Quality</h5>
                <p class="card-text">High Standard Products</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-white bg-success h-100">
              <div class="card-body">
                <h5 class="card-title">Best Deals</h5>
                <p class="card-text">Great prices on the latest products.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card text-white bg-danger h-100">
              <div class="card-body">
                <h5 class="card-title">Discounts</h5>
                <p class="card-text">Exclusive offers available now!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- Lower Section: Sidebar and Shop By Category -->
  <div class="container mt-4 lower-section">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3">
        <h5>Categories</h5>
        <ul class="list-group">
          <li class="list-group-item"><a href="#">Grains</a></li>
          <li class="list-group-item"><a href="#">Fruits</a></li>
          <li class="list-group-item"><a href="#">Vegetables</a></li>
        </ul>
      </div>

      <!-- Shop By Category -->
      <div class="col-md-9">
        <h5 class="mt-4">Shop By Category</h5>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card">
              <img src="https://img.freepik.com/free-photo/view-allergens-commonly-found-grains_23-2150170285.jpg" class="card-img-top" alt="Grains">
              <div class="card-body">
                <h6 class="card-title">Grains</h6>
                <p class="card-text">Find high-quality grains at the best price.</p>
                <a href="product-details.php" class="btn btn-primary">Explore</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card">
              <img src="https://images.everydayhealth.com/images/diet-nutrition/apples-101-about-1440x810.jpg?sfvrsn=f86f2644_5" class="card-img-top" alt="Fruits">
              <div class="card-body">
                <h6 class="card-title">Fruits</h6>
                <p class="card-text">Get fresh fruits directly from farms.</p>
                <a href="product-details.php" class="btn btn-primary">Explore</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card">
              <img src="https://www.simplotfoods.com/_next/image?url=https%3A%2F%2Fimages.ctfassets.net%2F0dkgxhks0leg%2F4LaYoCoepR6ZwEkAmQFh2F%2Fe82fa8e3c87f0e4cdb3e914b3e766fa0%2Fblog-large-2020veg.jpg%3Ffm%3Dwebp&w=1920&q=75" class="card-img-top" alt="Vegetables">
              <div class="card-body">
                <h6 class="card-title">Vegetables</h6>
                <p class="card-text">Fresh vegetables sourced locally.</p>
                <a href="product-details.php" class="btn btn-primary">Explore</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-light text-center py-3">
    <p>&copy; 2024 For Better Agriculture. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>