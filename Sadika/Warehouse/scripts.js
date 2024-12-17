document.addEventListener("DOMContentLoaded", function () {
    // Fetch and display warehouse data
    fetch('fetch_warehouse_data.php')
        .then(response => response.json())
        .then(data => {
            const warehouseData = document.getElementById('warehouseData');
            if (data.error) {
                warehouseData.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                warehouseData.innerHTML = data.map(warehouse => `
                    <div class="card">
                        <h3>${warehouse.zone}</h3>
                        <p>Location: ${warehouse.location}</p>
                        <p>Capacity: ${warehouse.capacity}</p>
                    </div>
                `).join('');
            }
        });

    // Fetch and display product data
    fetch('fetch_product_data.php')
        .then(response => response.json())
        .then(data => {
            const productData = document.getElementById('productData');
            if (data.error) {
                productData.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                productData.innerHTML = data.map(product => `
                    <div class="card">
                        <h3>${product.productName}</h3>
                        <p>Type: ${product.type}</p>
                        <p>Variety: ${product.variety || 'N/A'}</p>
                        <p>Seasonality: ${product.seasonality}</p>
                    </div>
                `).join('');
            }
        });

    // Fetch and display supply data
    fetch('fetch_supply_data.php')
        .then(response => response.json())
        .then(data => {
            const supplyData = document.getElementById('supplyData');
            if (data.error) {
                supplyData.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                supplyData.innerHTML = data.map(supply => `
                    <div class="card">
                        <h3>${supply.productName}</h3>
                        <p>Zone: ${supply.zone}</p>
                        <p>Location: ${supply.location}</p>
                        <p>Quantity: ${supply.quantity}</p>
                        <p>Unit Price: $${supply.unitPrice}</p>
                        <p>Date: ${supply.storageSupplyDate}</p>
                    </div>
                `).join('');
            }
        });
});
