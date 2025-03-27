<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Housekeeping Tasks</h1>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Filter Tasks</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/housekeeping/staffTasks') ?>" method="get">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= $date ?>">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Status</th>
                            <th>Scheduled Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No tasks assigned to you for this date</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $task): ?>
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
                                    <td><?= date('M d, Y', strtotime($task['scheduled_date'])) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateTaskModal<?= $task['id'] ?>">
                                            <i class="bi bi-pencil"></i> Update Status
                                        </button>
                                        
                                        <!-- Update Status Modal -->
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
                                                                <label for="status<?= $task['id'] ?>" class="form-label">Status</label>
                                                                <select class="form-select" id="status<?= $task['id'] ?>" name="status" required>
                                                                    <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                    <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                                                    <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="notes<?= $task['id'] ?>" class="form-label">Notes</label>
                                                                <textarea class="form-control" id="notes<?= $task['id'] ?>" name="notes" rows="3"><?= $task['notes'] ?? '' ?></textarea>
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
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
