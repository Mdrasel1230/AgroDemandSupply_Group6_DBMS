<?php
// Database connection //
$conn = new mysqli("localhost", "root", "", "agriculture_dsap");
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
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="https://dynamic.design.com/preview/logodraft/76e83145-8c75-4a6e-9b9b-ecad55c87096/image/large.png" alt="Logo" height="40">
            <span class="ms-2">For Better Agriculture</span>
        </a>
        <div class="d-flex align-items-center">
            <label class="me-2" for="locationSelector">Delivery to:</label>
            <select class="form-select form-select-sm me-4" id="locationSelector">
                <option selected>Mohammadpur</option>
                <option value="1">Shuchona</option>
                <option value="2">Society-06</option>
            </select>
        </div> 
        <div class="d-flex align-items-center">
            <input type="text" class="form-control me-2" placeholder="Search for 'products'" id="searchInput">
            <button class="btn btn-primary" id="searchButton">Search</button>
        </div>
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
    <!-- Summary Cards -->
    <div class="row">
      <div class="col-md-4">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h5 class="card-title">Total Inventory</h5>
            <p class="card-text">10,000 Units</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success">
          <div class="card-body">
            <h5 class="card-title">Active Warehouses</h5>
            <p class="card-text">12</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-danger">
          <div class="card-body">
            <h5 class="card-title">Total Products Available</h5>
            <p class="card-text">45</p>
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
  <script>
    document.getElementById('searchButton').addEventListener('click', function () {
        const searchTerm = document.getElementById('searchInput').value;
        fetch('your_php_script.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ searchTerm })
        })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
    });
  </script>
</body>
</html>
