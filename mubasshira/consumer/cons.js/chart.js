// Inventory Chart
const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
const inventoryChart = new Chart(inventoryCtx, {
    type: 'bar',
    data: {
        labels: ['Warehouse 1', 'Warehouse 2', 'Warehouse 3', 'Warehouse 4'],
        datasets: [{
            label: 'Inventory Levels',
            data: [2000, 1500, 3000, 2500],
            backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Price Trend Chart
const priceTrendCtx = document.getElementById('priceTrendChart').getContext('2d');
const priceTrendChart = new Chart(priceTrendCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Price Trends',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});
