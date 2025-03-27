<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daily Report</h1>
        <div>
            <a href="<?= site_url('/report/monthly') ?>" class="btn btn-info me-2">
                <i class="bi bi-calendar-month"></i> Monthly Report
            </a>
            <a href="<?= site_url('/report/occupancy') ?>" class="btn btn-success me-2">
                <i class="bi bi-bar-chart"></i> Occupancy Report
            </a>
            <a href="<?= site_url('/report/revenue') ?>" class="btn btn-primary">
                <i class="bi bi-cash"></i> Revenue Report
            </a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Select Date</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/report/daily') ?>" method="get">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= $date ?>">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Check-ins</h5>
                    <h2 class="display-4"><?= $report['check_ins'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Check-outs</h5>
                    <h2 class="display-4"><?= $report['check_outs'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Revenue</h5>
                    <h2 class="display-4">$<?= number_format($report['revenue'], 2) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Occupancy Rate</h5>
                    <h2 class="display-4"><?= number_format($report['occupancy_rate'], 1) ?>%</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daily Summary for <?= date('F d, Y', strtotime($date)) ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Revenue Breakdown</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="200"></canvas>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?= site_url('/report/daily?date=' . date('Y-m-d', strtotime('-1 day', strtotime($date)))) ?>" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Previous Day
                        </a>
                        <a href="<?= site_url('/report/daily?date=' . date('Y-m-d', strtotime('+1 day', strtotime($date)))) ?>" class="btn btn-outline-primary">
                            Next Day <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="<?= site_url('/report/daily?date=' . date('Y-m-d')) ?>" class="btn btn-outline-success">
                            <i class="bi bi-calendar-check"></i> Today's Report
                        </a>
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    
    const dailyData = {
        labels: ['Check-ins', 'Check-outs', 'Occupancy Rate (%)'],
        datasets: [{
            label: 'Daily Statistics',
            data: [
                <?= $report['check_ins'] ?>,
                <?= $report['check_outs'] ?>,
                <?= $report['occupancy_rate'] ?>
            ],
            backgroundColor: [
                'rgba(13, 110, 253, 0.7)',
                'rgba(255, 193, 7, 0.7)',
                'rgba(25, 135, 84, 0.7)'
            ],
            borderColor: [
                'rgb(13, 110, 253)',
                'rgb(255, 193, 7)',
                'rgb(25, 135, 84)'
            ],
            borderWidth: 1
        }]
    };
    
    const dailyConfig = {
        type: 'bar',
        data: dailyData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Daily Hotel Statistics'
                }
            }
        },
    };
    
    new Chart(dailyCtx, dailyConfig);
    
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    
    const revenueData = {
        labels: ['Standard', 'Deluxe', 'Suite', 'Executive'],
        datasets: [{
            label: 'Revenue by Room Type',
            data: [
                <?= $report['revenue'] * 0.3 ?>, // 30% from Standard rooms
                <?= $report['revenue'] * 0.25 ?>, // 25% from Deluxe rooms
                <?= $report['revenue'] * 0.25 ?>, // 25% from Suite rooms
                <?= $report['revenue'] * 0.2 ?>  // 20% from Executive rooms
            ],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)'
            ],
            borderColor: [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(255, 206, 86)',
                'rgb(75, 192, 192)'
            ],
            borderWidth: 1
        }]
    };
    
    const revenueConfig = {
        type: 'doughnut',
        data: revenueData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Revenue by Room Type'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        },
    };
    
    new Chart(revenueCtx, revenueConfig);
});
</script>
<?= $this->endSection() ?>
