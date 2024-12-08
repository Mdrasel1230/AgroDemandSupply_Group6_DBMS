let stockChart;
let employeeChart;

// Function to open the inventory modal
function openInventory() {
    const modal = document.getElementById('inventoryModal');
    modal.style.display = 'flex';

    const stockData = {
        labels: ['Wheat', 'Rice', 'Barley', 'Corn'],
        datasets: [{
            label: 'Stock Levels',
            data: [200, 150, 100, 250],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    };

    const ctx = document.getElementById('stockChart').getContext('2d');
    if (stockChart) stockChart.destroy();
    stockChart = new Chart(ctx, {
        type: 'bar',
        data: stockData,
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Function to open the orders modal
function openOrders() {
    const modal = document.getElementById('ordersModal');
    modal.style.display = 'flex';

    const orders = [
        { item: 'Wheat', incoming: 50, outgoing: 30 },
        { item: 'Rice', incoming: 70, outgoing: 40 },
        { item: 'Barley', incoming: 30, outgoing: 20 },
        { item: 'Corn', incoming: 60, outgoing: 25 }
    ];

    const tbody = document.getElementById('ordersTable').querySelector('tbody');
    tbody.innerHTML = '';

    orders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${order.item}</td>
            <td>${order.incoming}</td>
            <td>${order.outgoing}</td>
        `;
        tbody.appendChild(row);
    });
}

// Function to open the suppliers modal
function openSuppliers() {
    const modal = document.getElementById('suppliersModal');
    modal.style.display = 'flex';

    const suppliers = [
        { name: 'John Doe', id: 'SUP123', quantity: 500 },
        { name: 'Jane Smith', id: 'SUP456', quantity: 300 },
        { name: 'David Lee', id: 'SUP789', quantity: 700 },
        { name: 'Sophia Brown', id: 'SUP101', quantity: 400 }
    ];

    const tbody = document.getElementById('suppliersTable').querySelector('tbody');
    tbody.innerHTML = '';

    suppliers.forEach(supplier => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${supplier.name}</td>
            <td>${supplier.id}</td>
            <td>${supplier.quantity}</td>
        `;
        tbody.appendChild(row);
    });
}

// Function to open the employees modal
function openEmployees() {
    const modal = document.getElementById('employeesModal');
    modal.style.display = 'flex';

    const employeeData = {
        labels: ['John', 'Jane', 'David', 'Sophia'], // Employee names
        datasets: [{
            label: 'Assignments Completed',
            data: [15, 20, 12, 18], // Example assignment counts
            backgroundColor: [
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    };

    const ctx = document.getElementById('employeeChart').getContext('2d');
    if (employeeChart) employeeChart.destroy();
    employeeChart = new Chart(ctx, {
        type: 'bar',
        data: employeeData,
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

// Function to close modals
function closeModal() {
    document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
}
