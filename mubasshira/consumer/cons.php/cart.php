<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);
    $total = 0;

    echo "<h1>Cart Summary</h1>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>{$item['name']} - \${$item['price']}</li>";
        $total += $item['price'];
    }
    echo "</ul>";
    echo "<h3>Total: \$$total</h3>";
}
?>
