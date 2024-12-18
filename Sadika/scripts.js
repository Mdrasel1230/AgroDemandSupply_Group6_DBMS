function openInventory() {
    fetch('dashboard.php?action=getInventory')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Handle inventory data
            const labels = data.map(item => item.productName);
            const quantities = data.map(item => item.quantity);

            const ctx = document.getElementById('stockChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Stock Levels',
                        data: quantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                }
            });

            document.getElementById('inventoryModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching inventory:', error));
}

function openOrders() {
    fetch('dashboard.php?action=getOrders')
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#ordersTable tbody');
            tbody.innerHTML = ''; // Clear table

            data.forEach(order => {
                const row = `<tr>
                    <td>${order.item}</td>
                    <td>${order.incoming}</td>
                    <td>${order.outgoing}</td>
                </tr>`;
                tbody.innerHTML += row;
            });

            document.getElementById('ordersModal').style.display = 'block';
        })
        .catch(error => console.error('Error fetching orders:', error));
}

// Other fetch functions for suppliers and employees...
