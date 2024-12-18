<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Basic styling for the login form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('css/bg_picture.jpg'); /* Add agriculture background */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #333;
        }

        .login-container {
            width: 350px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container select {
            width: 92%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 5px;
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #45a049;
        }

        .login-container p {
            margin-top: 15px;
        }

        .login-container a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <?php
    session_start();
    include 'db_connect.php'; // Connect to the database

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        // Query to check user credentials and role
        $sql = "SELECT * FROM users WHERE username = ? AND role = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Start the session and set user data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the appropriate dashboard
            if ($user['role'] === 'admin') {
                header("Location: Rasel/Admin Dashboard/admin_dashboard.php");
            } elseif ($user['role'] === 'supplier') {
                header("Location: Marjana/Supplier/css/supplier_dashboard.html");
            } elseif ($user['role'] === 'consumer') {
                header("Location: mubasshira/consumer/agro_dsap/consumer-dashboard.php");
            } elseif ($user['role'] === 'warehouse') {
                header("Location: Sadika/Dashboard.php");
            } elseif ($user['role'] === 'retailer') {
                header("Location: Nusrat/retailer_dashboard.html");
            }
            exit();
        } else {
            $error = "Invalid username, password, or role.";
        }
    }
    ?>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <!-- Role Selection -->
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="supplier">Supplier</option>
            <option value="consumer">Consumer</option>
            <option value="warehouse">Warehouse</option>
            <option value="retailer">Retailer</option>
        </select>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
