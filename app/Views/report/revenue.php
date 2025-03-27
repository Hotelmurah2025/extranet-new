<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Revenue Report</h1>
        <div>
            <a href="<?= site_url('/report/daily') ?>" class="btn btn-info me-2">
                <i class="bi bi-calendar-day"></i> Daily Report
            </a>
            <a href="<?= site_url('/report/monthly') ?>" class="btn btn-success me-2">
                <i class="bi bi-calendar-month"></i> Monthly Report
            </a>
            <a href="<?= site_url('/report/occupancy') ?>" class="btn btn-primary">
                <i class="bi bi-bar-chart"></i> Occupancy Report
            </a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Select Date Range</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/report/revenue') ?>" method="get">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $startDate ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $endDate ?>">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Revenue Trend (<?= date('M d, Y', strtotime($startDate)) ?> - <?= date('M d, Y', strtotime($endDate)) ?>)</h5>
        </div>
        <div class="card-body">
            <canvas id="revenueChart" height="100"></canvas>
            
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalRevenue = 0;
                        foreach ($revenueData as $data): 
                            $totalRevenue += $data['revenue'];
                        ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($data['date'])) ?></td>
                                <td>$<?= number_format($data['revenue'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-primary">
                            <th>Total Revenue</th>
                            <th>$<?= number_format($totalRevenue, 2) ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const labels = [
        <?php foreach ($revenueData as $data): ?>
            '<?= date('M d', strtotime($data['date'])) ?>',
        <?php endforeach; ?>
    ];
    
    const revenues = [
        <?php foreach ($revenueData as $data): ?>
            <?= $data['revenue'] ?>,
        <?php endforeach; ?>
    ];
    
    const data = {
        labels: labels,
        datasets: [{
            label: 'Revenue ($)',
            data: revenues,
            backgroundColor: 'rgba(25, 135, 84, 0.2)',
            borderColor: 'rgb(25, 135, 84)',
            borderWidth: 1
        }]
    };
    
    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Daily Revenue'
                }
            }
        },
    };
    
    new Chart(ctx, config);
});
</script>
<?= $this->endSection() ?>
