<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Rooms</h5>
                    <h2 class="card-text"><?= $totalRooms ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Available Rooms</h5>
                    <h2 class="card-text"><?= $availableRooms ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Occupied Rooms</h5>
                    <h2 class="card-text"><?= $occupiedRooms ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Dirty Rooms</h5>
                    <h2 class="card-text"><?= $dirtyRooms ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Today's Check-ins</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($todayCheckIns)): ?>
                        <p class="text-muted">No check-ins scheduled for today.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Guest</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todayCheckIns as $reservation): ?>
                                        <tr>
                                            <td><?= $reservation['room_number'] ?></td>
                                            <td><?= $reservation['guest_name'] ?></td>
                                            <td>
                                                <a href="<?= site_url('/reservation/check_in/' . $reservation['id']) ?>" class="btn btn-sm btn-success">Check In</a>
                                                <a href="<?= site_url('/reservation/view/' . $reservation['id']) ?>" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Today's Check-outs</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($todayCheckOuts)): ?>
                        <p class="text-muted">No check-outs scheduled for today.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Guest</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($todayCheckOuts as $reservation): ?>
                                        <tr>
                                            <td><?= $reservation['room_number'] ?></td>
                                            <td><?= $reservation['guest_name'] ?></td>
                                            <td>
                                                <a href="<?= site_url('/reservation/check_out/' . $reservation['id']) ?>" class="btn btn-sm btn-warning">Check Out</a>
                                                <a href="<?= site_url('/reservation/view/' . $reservation['id']) ?>" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Housekeeping Tasks</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($housekeepingTasks)): ?>
                        <p class="text-muted">No housekeeping tasks scheduled for today.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($housekeepingTasks as $task): ?>
                                        <tr>
                                            <td><?= $task['room_number'] ?></td>
                                            <td>
                                                <?php if ($task['status'] === 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif ($task['status'] === 'in_progress'): ?>
                                                    <span class="badge bg-info">In Progress</span>
                                                <?php elseif ($task['status'] === 'completed'): ?>
                                                    <span class="badge bg-success">Completed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateTaskModal<?= $task['id'] ?>">
                                                    Update Status
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Modal for updating task status -->
                                        <div class="modal fade" id="updateTaskModal<?= $task['id'] ?>" tabindex="-1" aria-labelledby="updateTaskModalLabel<?= $task['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="updateTaskModalLabel<?= $task['id'] ?>">Update Task Status - Room <?= $task['room_number'] ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="<?= site_url('/housekeeping/updateStatus') ?>" method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                            <div class="mb-3">
                                                                <label for="status" class="form-label">Status</label>
                                                                <select class="form-select" id="status" name="status" required>
                                                                    <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                    <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                                                    <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="notes" class="form-label">Notes</label>
                                                                <textarea class="form-control" id="notes" name="notes" rows="3"><?= $task['notes'] ?? '' ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Occupancy Rate</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        <div class="text-center">
                            <div class="progress" style="height: 30px; width: 250px; margin-bottom: 15px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= min($occupancyRate, 100) ?>%;" 
                                    aria-valuenow="<?= $occupancyRate ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?= number_format($occupancyRate, 1) ?>%
                                </div>
                            </div>
                            <h1 class="display-4"><?= number_format($occupancyRate, 1) ?>%</h1>
                            <p class="text-muted">Today's Occupancy Rate</p>
                            <a href="<?= site_url('/report/occupancy') ?>" class="btn btn-sm btn-outline-success mt-2">View Detailed Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
