<?php
// Database connection
$host = "localhost";
$db = "agriculture_db";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'getInventory':
            getInventory($pdo);
            break;
        case 'getOrders':
            getOrders($pdo);
            break;
        case 'getSuppliers':
            getSuppliers($pdo);
            break;
        case 'getEmployees':
            getEmployees($pdo);
            break;
        default:
            echo json_encode(["error" => "Invalid action", "debug" => "Action received: $action"]);
            break;
    }
    exit;
}

function getInventory($pdo) {
    try {
        $query = "SELECT P.productName, S.quantity, S.unitPrice FROM STORAGE_SUPPLY S
                  JOIN PRODUCT P ON S.productID = P.productID";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch inventory", "debug" => $e->getMessage()]);
    }
}

function getOrders($pdo) {
    try {
        $query = "SELECT P.productName, R.quantity AS outgoing, S.quantity AS incoming FROM RETAIL_SUPPLY R
                  JOIN PRODUCT P ON R.productID = P.productID
                  LEFT JOIN STORAGE_SUPPLY S ON R.productID = S.productID";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch orders", "debug" => $e->getMessage()]);
    }
}

function getSuppliers($pdo) {
    try {
        $query = "SELECT name, supplierID, regionSupplied FROM SUPPLIER";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch suppliers", "debug" => $e->getMessage()]);
    }
}

function getEmployees($pdo) {
    try {
        $data = [
            ["name" => "John", "assignments" => 15],
            ["name" => "Jane", "assignments" => 20],
            ["name" => "David", "assignments" => 12],
            ["name" => "Sophia", "assignments" => 18]
        ];
        echo json_encode($data);
    } catch (Exception $e) {
        echo json_encode(["error" => "Failed to fetch employees", "debug" => $e->getMessage()]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Manager Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <h1>Warehouse Manager Dashboard</h1>
</header>

<div class="container">
    <div class="card" onclick="openInventory()">
        <h2>Inventory</h2>
        <p>View and manage stock levels.</p>
    </div>
    <div class="card" onclick="openOrders()">
        <h2>Orders</h2>
        <p>Track incoming and outgoing orders.</p>
    </div>
    <div class="card" onclick="openSuppliers()">
        <h2>Suppliers</h2>
        <p>Manage supplier details.</p>
    </div>
    <div class="card" onclick="openEmployees()">
        <h2>Employees</h2>
        <p>Track staff and assignments.</p>
    </div>
</div>

<div id="inventoryModal" class="modal">
    <div class="modal-content">
        <h2>Stock Levels</h2>
        <canvas id="stockChart" width="400" height="200"></canvas>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</div>

<div id="ordersModal" class="modal">
    <div class="modal-content">
        <h2>Incoming and Outgoing Orders</h2>
        <table id="ordersTable">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Incoming</th>
                    <th>Outgoing</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</div>

<div id="suppliersModal" class="modal">
    <div class="modal-content">
        <h2>Supplier Details</h2>
        <table id="suppliersTable">
            <thead>
                <tr>
                    <th>Supplier Name</th>
                    <th>Supplier ID</th>
                    <th>Region</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</div>

<div id="employeesModal" class="modal">
    <div class="modal-content">
        <h2>Staff and Assignments</h2>
        <canvas id="employeeChart" width="400" height="200"></canvas>
        <button class="close-btn" onclick="closeModal()">Close</button>
    </div>
</div>

<footer>
    <p>&copy; 2024 Warehouse Management. All rights reserved.</p>
</footer>

<script>
let stockChart;
let employeeChart;

function fetchData(action, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "dashboard.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (this.status === 200) {
            try {
                const response = JSON.parse(this.responseText);
                if (response.error) {
                    console.error("Error:", response.error);
                    console.debug("Debug info:", response.debug);
                } else {
                    callback(response);
                }
            } catch (e) {
                console.error("Failed to parse response:", this.responseText);
            }
        } else {
            console.error("Request failed with status:", this.status);
        }
    };
    xhr.send(`action=${action}`);
}

function openInventory() {
    const modal = document.getElementById('inventoryModal');
    modal.style.display = 'flex';

    fetchData('getInventory', (data) => {
        const stockData = {
            labels: data.map(item => item.productName),
            datasets: [{
                label: 'Stock Levels',
                data: data.map(item => parseFloat(item.quantity)),
                backgroundColor: ['rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        };

        const ctx = document.getElementById('stockChart').getContext('2d');
        if (stockChart) stockChart.destroy();
        stockChart = new Chart(ctx, {
            type: 'bar',
            data: stockData,
            options: { scales: { y: { beginAtZero: true } } }
        });
    });
}

function openOrders() {
    const modal = document.getElementById('ordersModal');
    modal.style.display = 'flex';

    fetchData('getOrders', (data) => {
        const tbody = document.getElementById('ordersTable').querySelector('tbody');
        tbody.innerHTML = '';
        data.forEach(order => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${order.productName}</td><td>${order.incoming || 0}</td><td>${order.outgoing || 0}</td>`;
            tbody.appendChild(row);
        });
    });
}

function openSuppliers() {
    const modal = document.getElementById('suppliersModal');
    modal.style.display = 'flex';

    fetchData('getSuppliers', (data) => {
        const tbody = document.getElementById('suppliersTable').querySelector('tbody');
        tbody.innerHTML = '';
        data.forEach(supplier => {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${supplier.name}</td><td>${supplier.supplierID}</td><td>${supplier.regionSupplied}</td>`;
            tbody.appendChild(row);
        });
    });
}

function openEmployees() {
    const modal = document.getElementById('employeesModal');
    modal.style.display = 'flex';

    fetchData('getEmployees', (data) => {
        const employeeData = {
            labels: data.map(item => item.name),
            datasets: [{
                label: 'Assignments Completed',
                data: data.map(item => parseInt(item.assignments)),
                backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)'],
                borderWidth: 1
            }]
        };

        const ctx = document.getElementById('employeeChart').getContext('2d');
        if (employeeChart) employeeChart.destroy();
        employeeChart = new Chart(ctx, {
            type: 'bar',
            data: employeeData,
            options: { scales: { y: { beginAtZero: true } } }
        });
    });
}

function closeModal() {
    document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
}
</script>

<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: #f4f4f4;
  color: #333;
}
header {
  background-color: #28a745;
  color: white;
  padding: 1rem 2rem;
  text-align: center;
}
.container {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  margin: 2rem;
}
.card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  flex: 1 1 calc(33.333% - 2rem);
  margin: 1rem;
  padding: 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: transform 0.2s;
}
.card:hover {
  transform: scale(1.05);
}
.card h2 {
  font-size: 1.5rem;
  margin: 0 0 1rem;
  color: #333;
}
.card p {
  font-size: 1rem;
  color: #666;
}
footer {
  background-color: #28a745;
  color: white;
  text-align: center;
  padding: 1rem 0;
  margin-top: 2rem;
}
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 80%;
  max-width: 500px;
  text-align: center;
}
.close-btn {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}
.close-btn:hover {
  background-color: #218838;
}
</style>

</body>
</html>
