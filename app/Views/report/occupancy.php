<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Occupancy Report</h1>
        <div>
            <a href="<?= site_url('/report/daily') ?>" class="btn btn-info me-2">
                <i class="bi bi-calendar-day"></i> Daily Report
            </a>
            <a href="<?= site_url('/report/monthly') ?>" class="btn btn-success me-2">
                <i class="bi bi-calendar-month"></i> Monthly Report
            </a>
            <a href="<?= site_url('/report/revenue') ?>" class="btn btn-primary">
                <i class="bi bi-cash"></i> Revenue Report
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
            <form action="<?= site_url('/report/occupancy') ?>" method="get">
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
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Occupancy Rate Trend (<?= date('M d, Y', strtotime($startDate)) ?> - <?= date('M d, Y', strtotime($endDate)) ?>)</h5>
                </div>
                <div class="card-body">
                    <canvas id="occupancyChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Summary Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>Average Occupancy:</div>
                        <div id="avgOccupancy">
                            <?php 
                                $total = 0;
                                foreach ($occupancyData as $data) {
                                    $total += $data['rate'];
                                }
                                $avg = count($occupancyData) > 0 ? $total / count($occupancyData) : 0;
                                echo number_format($avg, 1) . '%';
                            ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Highest Occupancy:</div>
                        <div id="maxOccupancy">
                            <?php 
                                $max = 0;
                                $maxDate = '';
                                foreach ($occupancyData as $data) {
                                    if ($data['rate'] > $max) {
                                        $max = $data['rate'];
                                        $maxDate = $data['date'];
                                    }
                                }
                                echo number_format($max, 1) . '% (' . date('M d', strtotime($maxDate)) . ')';
                            ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div>Lowest Occupancy:</div>
                        <div id="minOccupancy">
                            <?php 
                                $min = 100;
                                $minDate = '';
                                foreach ($occupancyData as $data) {
                                    if ($data['rate'] < $min) {
                                        $min = $data['rate'];
                                        $minDate = $data['date'];
                                    }
                                }
                                echo number_format($min, 1) . '% (' . date('M d', strtotime($minDate)) . ')';
                            ?>
                        </div>
                    </div>
                    <div class="progress mt-4 mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $avg ?>%;" 
                            aria-valuenow="<?= $avg ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($avg, 1) ?>%
                        </div>
                    </div>
                    <div class="text-center text-muted">Average Occupancy Rate</div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                        <a href="<?= site_url('/report/occupancy?start_date=' . date('Y-m-d', strtotime('-30 days'))) . '&end_date=' . date('Y-m-d') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-calendar"></i> Last 30 Days
                        </a>
                        <a href="<?= site_url('/report/occupancy?start_date=' . date('Y-m-01')) . '&end_date=' . date('Y-m-t') ?>" class="btn btn-outline-success">
                            <i class="bi bi-calendar-month"></i> Current Month
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detailed Occupancy Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="occupancyTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Occupancy Rate</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($occupancyData as $data): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($data['date'])) ?></td>
                                <td><?= number_format($data['rate'], 1) ?>%</td>
                                <td>
                                    <?php if ($data['rate'] >= 80): ?>
                                        <span class="badge bg-success">High</span>
                                    <?php elseif ($data['rate'] >= 50): ?>
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Low</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('occupancyChart').getContext('2d');
    
    const labels = [
        <?php foreach ($occupancyData as $data): ?>
            '<?= date('M d', strtotime($data['date'])) ?>',
        <?php endforeach; ?>
    ];
    
    const rates = [
        <?php foreach ($occupancyData as $data): ?>
            <?= $data['rate'] ?>,
        <?php endforeach; ?>
    ];
    
    const data = {
        labels: labels,
        datasets: [{
            label: 'Occupancy Rate (%)',
            data: rates,
            fill: true,
            backgroundColor: 'rgba(13, 202, 240, 0.2)',
            borderColor: 'rgb(13, 202, 240)',
            tension: 0.1,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    };
    
    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Occupancy Rate Trend'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + '%';
                        }
                    }
                }
            }
        },
    };
    
    new Chart(ctx, config);
    
    const occupancyTable = document.getElementById('occupancyTable');
    const rows = Array.from(occupancyTable.querySelectorAll('tbody tr'));
    
    document.querySelectorAll('#occupancyTable th').forEach((header, index) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            sortTable(index);
        });
    });
    
    function sortTable(columnIndex) {
        let sortDirection = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';
        
        document.querySelectorAll('#occupancyTable th').forEach(th => {
            th.dataset.sortDirection = '';
        });
        
        const header = document.querySelectorAll('#occupancyTable th')[columnIndex];
        header.dataset.sortDirection = sortDirection;
        
        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();
            
            if (aValue.includes('%') && bValue.includes('%')) {
                return sortDirection === 'asc' 
                    ? parseFloat(aValue) - parseFloat(bValue) 
                    : parseFloat(bValue) - parseFloat(aValue);
            }
            
            if (columnIndex === 0) {
                const aDate = new Date(aValue);
                const bDate = new Date(bValue);
                return sortDirection === 'asc' ? aDate - bDate : bDate - aDate;
            }
            
            return sortDirection === 'asc' 
                ? aValue.localeCompare(bValue) 
                : bValue.localeCompare(aValue);
        });
        
        const tbody = occupancyTable.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row));
    }
});
</script>
<?= $this->endSection() ?>
