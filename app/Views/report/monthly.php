<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Monthly Report</h1>
        <div>
            <a href="<?= site_url('/report/daily') ?>" class="btn btn-info me-2">
                <i class="bi bi-calendar-day"></i> Daily Report
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
                <h5 class="mb-0">Select Month</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/report/monthly') ?>" method="get">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="month" class="form-label">Month</label>
                        <select class="form-select" id="month" name="month">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= sprintf('%02d', $i) ?>" <?= $month == sprintf('%02d', $i) ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year">
                            <?php for ($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                                <option value="<?= $i ?>" <?= $year == $i ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Check-ins</h5>
                    <h2 class="display-4"><?= $report['check_ins'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2 class="display-4">$<?= number_format($report['revenue'], 2) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Average Occupancy Rate</h5>
                    <h2 class="display-4"><?= number_format($report['avg_occupancy_rate'], 1) ?>%</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Monthly Summary for <?= date('F Y', strtotime($year . '-' . $month . '-01')) ?></h5>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="100"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    
    const data = {
        labels: ['Check-ins', 'Revenue ($)', 'Avg. Occupancy Rate (%)'],
        datasets: [{
            label: 'Monthly Statistics',
            data: [
                <?= $report['check_ins'] ?>,
                <?= $report['revenue'] ?>,
                <?= $report['avg_occupancy_rate'] ?>
            ],
            backgroundColor: [
                'rgba(13, 110, 253, 0.7)',
                'rgba(25, 135, 84, 0.7)',
                'rgba(13, 202, 240, 0.7)'
            ],
            borderColor: [
                'rgb(13, 110, 253)',
                'rgb(25, 135, 84)',
                'rgb(13, 202, 240)'
            ],
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
                    text: 'Monthly Hotel Statistics'
                }
            }
        },
    };
    
    new Chart(ctx, config);
});
</script>
<?= $this->endSection() ?>
