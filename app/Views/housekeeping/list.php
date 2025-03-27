<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Housekeeping Tasks</h1>
        <div>
            <a href="<?= site_url('/housekeeping/schedule') ?>" class="btn btn-primary">
                <i class="bi bi-calendar-plus"></i> Schedule Tasks
            </a>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Filter Tasks</h5>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= site_url('/housekeeping') ?>" method="get">
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
                            <th>Assigned To</th>
                            <th>Scheduled Date</th>
                            <th>Completed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No housekeeping tasks found for this date</td>
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
                                    <td><?= $task['assigned_to_name'] ?? 'Not Assigned' ?></td>
                                    <td><?= date('M d, Y', strtotime($task['scheduled_date'])) ?></td>
                                    <td><?= $task['completed_date'] ? date('M d, Y H:i', strtotime($task['completed_date'])) : 'N/A' ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateTaskModal<?= $task['id'] ?>">
                                            <i class="bi bi-pencil"></i> Update Status
                                        </button>
                                        
                                        <?php if (!$task['assigned_to']): ?>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#assignTaskModal<?= $task['id'] ?>">
                                                <i class="bi bi-person-plus"></i> Assign
                                            </button>
                                        <?php endif; ?>
                                        
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
                                        
                                        <!-- Assign Task Modal -->
                                        <?php if (!$task['assigned_to']): ?>
                                            <div class="modal fade" id="assignTaskModal<?= $task['id'] ?>" tabindex="-1" aria-labelledby="assignTaskModalLabel<?= $task['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="assignTaskModalLabel<?= $task['id'] ?>">Assign Task - Room <?= $task['room_number'] ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="<?= site_url('/housekeeping/assign') ?>" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                                                <div class="mb-3">
                                                                    <label for="staff_id<?= $task['id'] ?>" class="form-label">Assign To</label>
                                                                    <select class="form-select" id="staff_id<?= $task['id'] ?>" name="staff_id" required>
                                                                        <option value="">Select Staff</option>
                                                                        <?php foreach ($staff ?? [] as $member): ?>
                                                                            <option value="<?= $member['id'] ?>"><?= $member['username'] ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Assign</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
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
