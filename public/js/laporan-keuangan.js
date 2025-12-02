// Daily Revenue Chart
const dailyRevenueCtx = document.getElementById('dailyRevenueChart');
if (dailyRevenueCtx && window.transaksiData) {
    new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: window.transaksiData.labels,
            datasets: [{
                label: 'Pemasukan Harian (Rp)',
                data: window.transaksiData.data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                        }
                    }
                }
            }
        }
    });
}
