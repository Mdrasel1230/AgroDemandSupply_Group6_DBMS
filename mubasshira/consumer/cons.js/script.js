window.onload = function() {
    fetchDataForChart();
};

function fetchDataForChart() {
    fetch('data_fetch.php')
        .then(response => response.json())
        .then(data => {
            const categories = data.categories;
            const sales = data.sales;

            renderChart(categories, sales);
        })
        .catch(error => console.error("Error fetching data: ", error));
}

function renderChart(categories, sales) {
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Sales Data',
                data: sales,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
