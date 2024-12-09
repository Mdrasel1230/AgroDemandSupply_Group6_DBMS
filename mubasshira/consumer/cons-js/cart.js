// Array to hold cart items
let cart = [];

// Function to add an item to the cart
function addItemToCart(product, quantity, price) {
  cart.push({ product, quantity, price });
  updateCartUI();
}

// Function to update Cart UI with items
function updateCartUI() {
  const cartItems = document.getElementById('cart-items');
  cartItems.innerHTML = '';
  let totalAmount = 0;

  // Loop through each item in the cart and update the list
  cart.forEach((item) => {
    const itemTotalPrice = item.price * item.quantity;
    totalAmount += itemTotalPrice;
    cartItems.innerHTML += `
      <li class="list-group-item">
        ${item.product} - ${item.quantity} kg - ৳${itemTotalPrice}
        <button class="btn btn-danger btn-sm" onclick="removeFromCart('${item.product}')">Remove</button>
      </li>
    `;
  });

  // Update the total amount in Taka
  document.getElementById('total-amount').textContent = `৳${totalAmount}`;
}

// Function to remove an item from the cart
function removeFromCart(product) {
  cart = cart.filter((item) => item.product !== product);
  updateCartUI();
}

// Function to confirm the order
function confirmOrder() {
  // Hide confirm order button and show checkout button
  document.getElementById('confirm-order-btn').style.display = 'none';
  document.getElementById('checkout-btn').style.display = 'inline-block';
}

// Function to handle proceeding to checkout
function proceedToCheckout() {
  // Generate a random Order ID
  const orderId = 'ORD' + Math.floor(Math.random() * 1000000);
  document.getElementById('order-id').textContent = orderId;

  // Show order confirmation message
  document.getElementById('order-confirmation').style.display = 'block';

  // Hide the cart and checkout buttons
  document.getElementById('cart-items').style.display = 'none';
  document.getElementById('order-summary').style.display = 'none';
  document.getElementById('checkout-btn').style.display = 'none';
}

// Function to load the cart from localStorage when the page loads
window.onload = function() {
  // Get cart data from localStorage
  const storedCart = localStorage.getItem('cart');
  if (storedCart) {
    cart = JSON.parse(storedCart);
  }
  updateCartUI();
}

// Function to save cart to localStorage
function saveCartToLocalStorage() {
  localStorage.setItem('cart', JSON.stringify(cart));
}

// Function to redirect after adding items to the cart
function redirectToCartPage() {
  saveCartToLocalStorage();
  window.location.href = "cart.html"; // Assuming this is the cart page URL
}
