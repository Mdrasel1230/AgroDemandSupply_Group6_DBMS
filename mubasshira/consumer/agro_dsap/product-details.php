<?php
// Start session to manage cart data
session_start();

// Mock product data (in real-world scenarios, fetch from a database)
$products = [
    ["id" => 1, "category" => "Grains", "name" => "Wheat", "price" => 50, "unit" => "kg", "image" => "https://www.realsimple.com/thmb/P9xeLZLcOPKOi5EQSHGa-VhOlB0=/750x0/filters:no_upscale():max_bytes(150000):strip_icc():format(webp)/is-wheat-bread-healthy-GettyImages-1488687097-b92bc2286fe7401e8e40602b627525b8.jpg", "description" => "High-quality wheat for your daily needs.", "rating" => 4.5],
    ["id" => 2, "category" => "Grains", "name" => "Rice", "price" => 60, "unit" => "kg", "image" => "https://cdn.prod.website-files.com/66e9e86e939e026869639119/66fc4e47b5d69fb0deb88654_iStock-153737841-scaled.jpeg", "description" => "Premium rice for cooking every meal.", "rating" => 4.7],
    ["id" => 3, "category" => "Fruits", "name" => "Apple", "price" => 100, "unit" => "kg", "image" => "https://images.everydayhealth.com/images/diet-nutrition/apples-101-about-1440x810.jpg?sfvrsn=f86f2644_5", "description" => "Fresh, sweet apples harvested at peak ripeness.", "rating" => 4.8],
    ["id" => 4, "category" => "Fruits", "name" => "Banana", "price" => 40, "unit" => "dozen", "image" => "https://www.chandigarhayurvedcentre.com/wp-content/uploads/2024/04/img_193775_bananas.jpg", "description" => "Healthy, ripe bananas packed with nutrients.", "rating" => 4.3],
    ["id" => 5, "category" => "Vegetables", "name" => "Carrot", "price" => 45, "unit" => "kg", "image" => "https://images.food52.com/bINxPtItEhhltoIaoTN3fbXsrAs=/1320x880/filters:format(webp)/51b308bf-5c78-4bc3-8c7a-57f49dbbc542--2017-1010_brazilian-carrot-cake-genius-recipes_julia-gartland-085.jpg", "description" => "Fresh carrots perfect for salads or cooking.", "rating" => 4.6],
    ["id" => 6, "category" => "Vegetables", "name" => "Tomato", "price" => 70, "unit" => "kg", "image" => "https://as1.ftcdn.net/v2/jpg/03/13/56/38/1000_F_313563819_KB3fG1pZOz9QxiCgEpTrvhFfbB6vqyf1.jpg", "description" => "Organic tomatoes with a perfect balance of sweetness and tartness.", "rating" => 4.9]
];

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    if ($action === 'addToCart') {
        $productId = intval($_POST['productId']);
        $quantity = floatval($_POST['quantity']);
        
        if ($quantity > 0) {
            $product = array_filter($products, fn($p) => $p['id'] === $productId);
            
            if ($product) {
                $product = array_values($product)[0];

                // Add to cart
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $cart = &$_SESSION['cart'];

                if (isset($cart[$productId])) {
                    $cart[$productId]['quantity'] += $quantity;
                } else {
                    $cart[$productId] = [
                        "id" => $productId,
                        "name" => $product['name'],
                        "price" => $product['price'],
                        "unit" => $product['unit'],
                        "quantity" => $quantity,
                        "image" => $product['image']
                    ];
                }
            }
        }
    } elseif ($action === 'clearCart') {
        $_SESSION['cart'] = [];
    } elseif ($action === 'confirmOrder') {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $order = [
                "orderId" => time(),
                "cart" => $_SESSION['cart'],
                "total" => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart'])),
                "date" => date('Y-m-d H:i:s')
            ];

            // In real applications, save the order to the database

            $_SESSION['cart'] = [];
            echo json_encode(["success" => true, "orderId" => $order['orderId']]);
            exit;
        } else {
            echo json_encode(["success" => false, "message" => "Cart is empty"]);
            exit;
        }
    }
}

// Retrieve cart data for AJAX calls
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getCart') {
    echo json_encode(["cart" => $_SESSION['cart'] ?? []]);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .navbar {
      background: linear-gradient(90deg, #285b49, #3b725f);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      color: white !important;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .category-list {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .category-list.active,
    .category-list:hover {
      background-color: #285b49;
      color: white;
      box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);
    }

    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 20px;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    #cartSidebar {
      position: fixed;
      top: 60px;
      right: 0;
      width: 300px;
      height: 100%;
      background-color: #ffffff;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
      padding: 15px;
      overflow-y: auto;
      transition: transform 0.3s ease;
      transform: translateX(100%);
      z-index: 1050;
    }

    #cartSidebar.show {
      transform: translateX(0);
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
      margin-bottom: 10px;
    }

    .cart-header h4 {
      margin: 0;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 5px;
    }

    .cart-item img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 5px;
    }

    .cart-item-info {
      flex: 1;
      margin-left: 10px;
    }

    footer {
      margin-top: 50px;
      text-align: center;
      padding: 15px;
      background: linear-gradient(90deg, #285b49, #3b725f);
      color: white;
    }

    .notification {
      position: fixed;
      top: 10px;
      right: 10px;
      background-color: #28a745;
      color: white;
      padding: 10px;
      border-radius: 5px;
      display: none;
      z-index: 1050;
    }

    .rating {
      color: #ffcc00;
    }

    .product-description {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .product-price {
      font-size: 1.2rem;
      color: #28a745;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="fas fa-seedling"></i> For Better Agriculture </a>
      <button class="btn btn-primary" onclick="toggleCart()">View Cart</button>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container my-4">
    <div class="row">
      <div class="col-md-3">
        <h3>Categories</h3>
        <ul class="list-group">
          <li class="list-group-item category-list active" onclick="filterProducts('')">All Products</li>
          <li class="list-group-item category-list" onclick="filterProducts('Grains')">Grains</li>
          <li class="list-group-item category-list" onclick="filterProducts('Fruits')">Fruits</li>
          <li class="list-group-item category-list" onclick="filterProducts('Vegetables')">Vegetables</li>
        </ul>
      </div>

      <div class="col-md-9">
        <div class="row" id="productList"></div>
      </div>
    </div>
  </div>

  <!-- Cart Sidebar -->
  <div id="cartSidebar">
    <div class="cart-header">
      <h4>Your Cart</h4>
      <button class="btn btn-close" onclick="toggleCart()"></button>
    </div>
    <div id="cartContent"></div>
    <h5 class="mt-3">Total: TK <span id="totalPrice">0</span></h5>
    <button class="btn btn-danger w-100 mt-3" onclick="clearCart()">Clear Cart</button>
    <button class="btn btn-success w-100 mt-3" onclick="confirmOrder()">Confirm Order</button>
  </div>

  <div class="notification" id="notification">Product added to cart!</div>

  <footer>
    <p>© 2024 For Better Agriculture. All Rights Reserved.</p>
  </footer>

  <script>
    const products = [
      { id: 1, category: 'Grains', name: 'Wheat', price: 50, unit: 'kg', image: 'https://as2.ftcdn.net/v2/jpg/06/39/92/49/1000_F_639924962_NstXVrDvOHmfM42mSHoydSp0v1Ac1uF9.jpg', description: 'High-quality wheat for your daily needs.', rating: 4.5 },
      { id: 2, category: 'Grains', name: 'Rice', price: 60, unit: 'kg', image: 'https://cdn.prod.website-files.com/66e9e86e939e026869639119/66fc4e47b5d69fb0deb88654_iStock-153737841-scaled.jpeg', description: 'Premium rice for cooking every meal.', rating: 4.7 },
      { id: 3, category: 'Fruits', name: 'Apple', price: 100, unit: 'kg', image: 'https://images.everydayhealth.com/images/diet-nutrition/apples-101-about-1440x810.jpg?sfvrsn=f86f2644_5', description: 'Fresh, sweet apples harvested at peak ripeness.', rating: 4.8 },
      { id: 4, category: 'Fruits', name: 'Banana', price: 40, unit: 'dozen', image: 'https://www.chandigarhayurvedcentre.com/wp-content/uploads/2024/04/img_193775_bananas.jpg', description: 'Healthy, ripe bananas packed with nutrients.', rating: 4.3 },
      { id: 5, category: 'Vegetables', name: 'Carrot', price: 45, unit: 'kg', image: 'https://images.food52.com/bINxPtItEhhltoIaoTN3fbXsrAs=/1320x880/filters:format(webp)/51b308bf-5c78-4bc3-8c7a-57f49dbbc542--2017-1010_brazilian-carrot-cake-genius-recipes_julia-gartland-085.jpg', description: 'Fresh carrots perfect for salads or cooking.', rating: 4.6 },
      { id: 6, category: 'Vegetables', name: 'Tomato', price: 70, unit: 'kg', image: 'https://as1.ftcdn.net/v2/jpg/03/13/56/38/1000_F_313563819_KB3fG1pZOz9QxiCgEpTrvhFfbB6vqyf1.jpg', description: 'Organic tomatoes with a perfect balance of sweetness and tartness.', rating: 4.9 }
    ];

    const cart = [];
    let orderId = 0;

    function displayProducts(category) {
      const productList = document.getElementById('productList');
      productList.innerHTML = '';
      products
        .filter(product => !category || product.category === category)
        .forEach(product => {
          productList.innerHTML += `
            <div class="col-md-4">
              <div class="card product-card">
                <img src="${product.image}" class="card-img-top" alt="${product.name}">
                <div class="card-body">
                  <h5 class="card-title">${product.name}</h5>
                  <p class="product-description">${product.description}</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <p class="product-price">TK ${product.price} per ${product.unit}</p>
                    <div class="rating">
                      ${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))}
                    </div>
                  </div>
                  <input type="number" id="qty-${product.id}" class="form-control" placeholder="Quantity" min="1">
                  <button class="btn btn-success w-100 mt-2" onclick="addToCart(${product.id})">Add to Cart</button>
                </div>
              </div>
            </div>
          `;
        });
    }

    function filterProducts(category) {
      const categoryListItems = document.querySelectorAll('.category-list');
      categoryListItems.forEach(item => item.classList.remove('active'));
      const selectedItem = document.querySelector(`[onclick="filterProducts('${category}')"]`);
      if (selectedItem) selectedItem.classList.add('active');
      displayProducts(category);
    }

    function addToCart(productId) {
      const product = products.find(p => p.id === productId);
      const qty = parseFloat(document.getElementById(`qty-${productId}`).value);
      if (qty > 0) {
        const cartItem = cart.find(item => item.id === productId);
        if (cartItem) {
          cartItem.quantity += qty;
        } else {
          cart.push({ ...product, quantity: qty });
        }
        updateCart();
        showNotification();
      } else {
        alert('Please enter a valid quantity!');
      }
    }

    function updateCart() {
      const cartContent = document.getElementById('cartContent');
      const totalPriceElement = document.getElementById('totalPrice');
      cartContent.innerHTML = '';
      let totalPrice = 0;

      cart.forEach(item => {
        cartContent.innerHTML += `
          <div class="cart-item">
            <img src="${item.image}" alt="${item.name}">
            <div class="cart-item-info">
              <h6>${item.name}</h6>
              <small>${item.quantity} ${item.unit} - TK ${(item.price * item.quantity).toFixed(2)}</small>
            </div>
          </div>
        `;
        totalPrice += item.price * item.quantity;
      });

      totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    function showCartPreview() {
      const cartContent = document.getElementById('cartContent');
      cartContent.innerHTML = ''; // clear preview

      cart.forEach(item => {
        cartContent.innerHTML += `
          <div class="cart-item">
            <img src="${item.image}" alt="${item.name}">
            <div class="cart-item-info">
              <h6>${item.name}</h6>
              <small>${item.quantity} ${item.unit} - TK ${(item.price * item.quantity).toFixed(2)}</small>
            </div>
          </div>
        `;
      });
    }

    function clearCart() {
      cart.length = 0;
      updateCart();
      showCartPreview();
    }

    function confirmOrder() {
      if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
      }

      orderId = Date.now();
      localStorage.setItem('orderId', orderId);
      localStorage.setItem('cart', JSON.stringify(cart));

      alert('Order confirmed successfully!');
      window.location.href = 'cart.php';
    }

    function toggleCart() {
      const cartSidebar = document.getElementById('cartSidebar');
      cartSidebar.classList.toggle('show');
      showCartPreview();
    }

    function showNotification() {
      const notification = document.getElementById('notification');
      notification.style.display = 'block';
      setTimeout(() => {
        notification.style.display = 'none';
      }, 2000);
    }

    window.onload = () => displayProducts('');
  </script>
</body>
</html>