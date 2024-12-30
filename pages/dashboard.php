<?php
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


date_default_timezone_set('Asia/Jakarta');


$stmt = $conn->query("SELECT COUNT(*) as total FROM products");
$totalProducts = $stmt->fetch()['total'];


$stmt = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock <= min_stock");
$lowStock = $stmt->fetch()['total'];


$stmt = $conn->query("SELECT COUNT(*) as total FROM suppliers");
$totalSuppliers = $stmt->fetch()['total'];


$stmt = $conn->query("SELECT COUNT(*) as total FROM categories");
$totalCategories = $stmt->fetch()['total'];


$stmt = $conn->query("
    SELECT COUNT(*) as total, COALESCE(SUM(total_amount), 0) as revenue 
    FROM sales 
    WHERE DATE(date) = CURDATE()
");
$todaySales = $stmt->fetch();


$stmt = $conn->query("
    SELECT 
        sm.*,
        p.name as product_name,
        u.username,
        DATE_FORMAT(sm.date, '%Y-%m-%d %H:%i:%s') as formatted_date
    FROM stock_movements sm
    JOIN products p ON sm.product_id = p.id
    JOIN users u ON sm.user_id = u.id
    ORDER BY sm.date DESC
    LIMIT 5
");
$recentMovements = $stmt->fetchAll();


$stmt = $conn->query("
    SELECT 
        p.name,
        SUM(sd.quantity) as total_sold,
        SUM(sd.subtotal) as total_revenue
    FROM sales_details sd
    JOIN products p ON sd.product_id = p.id
    JOIN sales s ON sd.sale_id = s.id
    WHERE MONTH(s.date) = MONTH(CURRENT_DATE())
    GROUP BY p.id
    ORDER BY total_sold DESC
    LIMIT 5
");
$topProducts = $stmt->fetchAll();


$period = isset($_GET['period']) ? $_GET['period'] : '7days';

$periodQuery = "";
$groupBy = "";
$dateSelect = "";

switch ($period) {
    case 'today':
        $stmt = $conn->query("
            SELECT 
                DATE_FORMAT(s.date, '%Y-%m-%d %H:%i:%s') as sale_date,
                p.name as product_name,
                sd.quantity as quantity_sold,
                sd.subtotal as revenue,
                s.date as original_date
            FROM sales s
            JOIN sales_details sd ON s.id = sd.sale_id
            JOIN products p ON sd.product_id = p.id
            WHERE DATE(s.date) = CURDATE()
            ORDER BY s.date ASC
        ");
        break;
    case '7days':
        $stmt = $conn->query("
            SELECT 
                DATE(s.date) as sale_date,
                COUNT(DISTINCT s.id) as total_transactions,
                SUM(s.total_amount) as daily_revenue
            FROM sales s
            WHERE s.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(s.date)
            ORDER BY sale_date
        ");
        break;
    case '30days':
        $stmt = $conn->query("
            SELECT 
                DATE(s.date) as sale_date,
                COUNT(DISTINCT s.id) as total_transactions,
                SUM(s.total_amount) as daily_revenue
            FROM sales s
            WHERE s.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(s.date)
            ORDER BY sale_date
        ");
        break;
    case 'this_month':
        $stmt = $conn->query("
            SELECT 
                DATE(s.date) as sale_date,
                COUNT(DISTINCT s.id) as total_transactions,
                SUM(s.total_amount) as daily_revenue
            FROM sales s
            WHERE MONTH(s.date) = MONTH(CURRENT_DATE()) 
            AND YEAR(s.date) = YEAR(CURRENT_DATE())
            GROUP BY DATE(s.date)
            ORDER BY sale_date
        ");
        break;
}

$periodSales = $stmt->fetchAll();


?>

<!-- Dashboard Header -->
<div class="content-header">
    <div class="content-header-left">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
    </div>
    <div class="user-profile">
        <span class="user-name">Miftakhul Rahman</span>
        <div class="profile-image">
            <img src="../assets/img/profil.jpg" alt="Profile" class="avatar">
            <span class="status-dot"></span>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="dashboard-container">
    <div class="dashboard-stats">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3>Total Produk</h3>
                <p><?php echo $totalProducts; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>Stok Menipis</h3>
                <p><?php echo $lowStock; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stat-info">
                <h3>Total Supplier</h3>
                <p><?php echo $totalSuppliers; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3>Total Kategori</h3>
                <p><?php echo $totalCategories; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>Penjualan Hari Ini</h3>
                <p><?php echo $todaySales['total']; ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3>Pendapatan Hari Ini</h3>
                <p>Rp <?php echo number_format($todaySales['revenue'], 0, ',', '.'); ?></p>
            </div>
        </div>
    </div>

    <!-- Section Chart dan Tabel -->
    <div class="dashboard-flex">
        <!-- Kolom kiri -->
        <div class="dashboard-col">
            <!-- Chart Penjualan -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-line"></i> Grafik Penjualan</h3>
                    <div class="chart-period-selector">
                        <select id="periodSelector" onchange="window.location.href='?period=' + this.value">
                            <option value="today" <?php echo $period == 'today' ? 'selected' : ''; ?>>Hari Ini</option>
                            <option value="7days" <?php echo $period == '7days' ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                            <option value="30days" <?php echo $period == '30days' ? 'selected' : ''; ?>>30 Hari Terakhir</option>
                            <option value="this_month" <?php echo $period == 'this_month' ? 'selected' : ''; ?>>Bulan Ini</option>
                        </select>
                    </div>
                </div>
                <canvas id="salesChart"></canvas>
            </div>

            <!-- Tabel Pergerakan Stok -->
            <div class="table-container">
                <div class="table-header">
                    <h3><i class="fas fa-history"></i> Pergerakan Stok Terakhir</h3>
                </div>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th><i class="far fa-calendar-alt"></i> Tanggal</th>
                            <th><i class="fas fa-box"></i> Produk</th>
                            <th><i class="fas fa-exchange-alt"></i> Tipe</th>
                            <th><i class="fas fa-sort-amount-up"></i> Jumlah</th>
                            <th><i class="fas fa-user"></i> User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMovements as $movement): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($movement['formatted_date'])); ?></td>
                                <td><?php echo $movement['product_name']; ?></td>
                                <td>
                                    <?php if ($movement['type'] == 'in'): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-arrow-down"></i> Masuk
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-arrow-up"></i> Keluar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $movement['quantity']; ?></td>
                                <td><?php echo $movement['username']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan -->
        <div class="dashboard-col">
            <!-- Top Produk -->
            <div class="table-container">
                <div class="table-header">
                    <h3><i class="fas fa-star"></i> Produk Terlaris Bulan Ini</h3>
                </div>
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-box"></i> Produk</th>
                            <th><i class="fas fa-shopping-cart"></i> Terjual</th>
                            <th><i class="fas fa-dollar-sign"></i> Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topProducts as $product): ?>
                            <tr>
                                <td><?php echo $product['name']; ?></td>
                                <td class="text-center"><?php echo $product['total_sold']; ?></td>
                                <td class="text-right">Rp <?php echo number_format($product['total_revenue'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Stok menipis -->
            <?php if ($lowStock > 0): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i>
                    <strong>Perhatian!</strong> Terdapat <?php echo $lowStock; ?> produk dengan stok menipis.
                    <a href="/pages/products/index.php" class="alert-link">Lihat detail</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>

    .dashboard-container {
        padding: 1rem;
        background: #f8f9fc;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .stat-icon i {
        font-size: 1.25rem;
        color: white;
    }


    .stat-icon.primary {
        background: #4e73df;
    }

    .stat-icon.warning {
        background: #f6c23e;
    }

    .stat-icon.success {
        background: #1cc88a;
    }

    .stat-icon.info {
        background: #36b9cc;
    }

    .stat-icon.purple {
        background: #6f42c1;
    }

    .stat-icon.orange {
        background: #fd7e14;
    }


    .badge-success {
        background: #4e73df;
        color: white;
    }

    .badge-danger {
        background: #e74a3b;
        color: white;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        border-radius: 6px;
    }

    .stat-info h3 {
        margin: 0;
        font-size: 0.875rem;
        color: #5a5c69;
        font-weight: 600;
    }

    .stat-info p {
        margin: 0.25rem 0 0;
        font-size: 1.25rem;
        font-weight: bold;
        color: #2e2f34;
    }


    .dashboard-flex {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .dashboard-col {
        flex: 1;
        min-width: 0;

    }


    .chart-container {
        background: white;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        height: 300px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .chart-header h3 {
        margin: 0;
        font-size: 1rem;
        color: #4a5568;
    }

    .chart-period-selector select {
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background-color: white;
        color: #4a5568;
        font-size: 0.875rem;
        cursor: pointer;
    }

    .chart-period-selector select:hover {
        border-color: #cbd5e0;
    }

    .chart-period-selector select:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }


    .table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .table-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .table-header h3 {
        margin: 0;
        font-size: 1rem;
        color: #4a5568;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table th,
    .dashboard-table td {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .dashboard-table th {
        background: #f8f9fc;
        font-weight: 600;
        color: #4a5568;
        text-align: left;
    }

    .dashboard-table tbody tr:hover {
        background: #f8f9fc;
    }

    /* Badges */
    .badge {
        padding: 0.35em 0.65em
    }
</style>


<!-- js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const salesData = <?php echo json_encode($periodSales); ?>;
    const currentPeriod = '<?php echo $period; ?>';


    let chartData;
    if (currentPeriod === 'today') {

        const hourlyData = {};

        salesData.forEach(sale => {
            const date = new Date(sale.original_date);
            const hour = date.getHours(); 
            const hourLabel = `${hour.toString().padStart(2, '0')}:00`;

            if (!hourlyData[hourLabel]) {
                hourlyData[hourLabel] = 0;
            }

            hourlyData[hourLabel] += parseFloat(sale.revenue);
        });


        const timePoints = Object.keys(hourlyData).sort();
        const revenueData = timePoints.map(hour => hourlyData[hour]);

        chartData = {
            labels: timePoints,
            datasets: [{
                label: 'Pendapatan Total per Jam',
                data: revenueData,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };
    } else {

        chartData = {
            labels: salesData.map(item => {
                const date = new Date(item.sale_date);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                });
            }),
            datasets: [{
                label: 'Pendapatan Harian',
                data: salesData.map(item => item.daily_revenue),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };
    }


    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            animation: {
                duration: 1000
            }
        }
    });


    if (currentPeriod === 'today') {
        setInterval(() => {
            window.location.reload();
        }, 30000); 
    }
</script>


<?php require_once '../includes/footer.php'; ?>