// Example: Add to Cart Functionality
function addToCart() {
  const quantity = document.getElementById('quantity').value;
  alert(`${quantity} kg of this product has been added to your cart.`);
}

// Search Functionality
document.querySelector('.form-control').addEventListener('keyup', (e) => {
  const searchTerm = e.target.value.toLowerCase();
  console.log(`Searching for: ${searchTerm}`);
  // Implement search logic here
});

  