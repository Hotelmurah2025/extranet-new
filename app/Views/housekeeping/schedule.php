<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Schedule Housekeeping Tasks</h1>
        <a href="<?= site_url('/housekeeping') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tasks
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('/housekeeping/createTask') ?>" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="room_id" class="form-label">Room</label>
                        <select class="form-select" id="room_id" name="room_id" required>
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room['id'] ?>">
                                    <?= $room['room_number'] ?> - <?= $room['room_type'] ?> (<?= $room['status'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('room_id')): ?>
                            <div class="text-danger"><?= $validation->getError('room_id') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="scheduled_date" class="form-label">Scheduled Date</label>
                        <input type="date" class="form-control" id="scheduled_date" name="scheduled_date" value="<?= date('Y-m-d') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('scheduled_date')): ?>
                            <div class="text-danger"><?= $validation->getError('scheduled_date') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="assigned_to" class="form-label">Assign To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Select Staff (Optional)</option>
                            <?php foreach ($staff as $member): ?>
                                <option value="<?= $member['id'] ?>"><?= $member['username'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Schedule Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
