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
    <title>User Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/common.css">
</head>
<body>
    <header>
        <h1>User Management</h1>
    </header>

    <?php include 'navbar.html'; ?>
    <?php include 'db_connect.php'; ?>

    <main>
        <!-- Add User Form -->
        <section>
            <h2>Add New User</h2>
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="supplier">Supplier</option>
                    <option value="consumer">Consumer</option>
                    <option value="warehouse">Warehouse</option>
                    <option value="retailer">Retailer</option>
                </select>
                
                <button type="submit" name="addUser">Add User</button>
            </form>

            <?php
            // Handle form submission for adding a new user
            if (isset($_POST['addUser'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $role = $_POST['role'];

                $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>New user added successfully!</p>";
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            }
            ?>
        </section>

        <!-- Display Users -->
        <section>
            <h2>User List</h2>
            <?php
            // Handle Delete
            if (isset($_GET['delete'])) {
                $userID = $_GET['delete'];
                $sql = "DELETE FROM users WHERE id='$userID'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>User deleted successfully!</p>";
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            }

            // Fetch users from the database
            $sql = "SELECT id, username, email, role FROM users";
            $result = $conn->query($sql);
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>"><button>Edit</button></a>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');"><button>Delete</button></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php
            // Handle Edit
            if (isset($_GET['edit'])) {
                $userID = $_GET['edit'];
                $sql = "SELECT * FROM users WHERE id='$userID'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
            ?>
            <h3>Edit User</h3>
            <form method="POST" action="">
                <input type="hidden" name="userID" value="<?= $row['id'] ?>">

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($row['username']) ?>" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" value="" required><br><br>

                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="supplier" <?= $row['role'] == 'supplier' ? 'selected' : '' ?>>Supplier</option>
                    <option value="consumer" <?= $row['role'] == 'consumer' ? 'selected' : '' ?>>Consumer</option>
                    <option value="warehouse" <?= $row['role'] == 'warehouse' ? 'selected' : '' ?>>Warehouse</option>
                    <option value="retailer" <?= $row['role'] == 'retailer' ? 'selected' : '' ?>>Retailer</option>
                </select><br><br>

                <button type="submit" name="updateUser">Update User</button>
            </form>
            <?php } ?>

            <?php
            // Handle Update
            if (isset($_POST['updateUser'])) {
                $userID = $_POST['userID'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $role = $_POST['role'];

                $sql = "UPDATE users SET username='$username', email='$email', password='$password', role='$role' WHERE id='$userID'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p>User updated successfully!</p>";
                } else {
                    echo "<p>Error: " . $conn->error . "</p>";
                }
            }
            ?>
        </section>
    </main>
</body>
</html>
