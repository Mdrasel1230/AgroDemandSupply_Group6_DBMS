document.addEventListener("DOMContentLoaded", () => {
    // Smooth scroll for navigation links
    const navLinks = document.querySelectorAll(".nav-links a");
    navLinks.forEach(link => {
        link.addEventListener("click", event => {
            event.preventDefault();
            const targetId = link.getAttribute("href").substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.scrollIntoView({ behavior: "smooth" });
            }
        });
    });

    // Handle product form submission
    const productForm = document.getElementById("product-form");
    const productGrid = document.querySelector(".grid-container");

    productForm.addEventListener("submit", event => {
        event.preventDefault(); // Prevent form reload
        const productName = document.getElementById("product-name").value.trim();
        const productColor = document.getElementById("product-color").value;

        if (productName) {
            const newProduct = document.createElement("div");
            newProduct.className = "product-item";
            newProduct.style.background = productColor;
            newProduct.textContent = productName;

            // Add click event to new product
            newProduct.addEventListener("click", () => {
                alert(`You clicked on ${productName}!`);
            });

            productGrid.appendChild(newProduct);

            // Reset form fields
            productForm.reset();
        } else {
            alert("Please enter a product name.");
        }
    });

    // Add click events to existing product items
    const productItems = document.querySelectorAll(".product-item");
    productItems.forEach(item => {
        item.addEventListener("click", () => {
            const productName = item.textContent.trim();
            alert(`You clicked on ${productName}!`);
        });
    });
});
