<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Room</h1>
        <a href="<?= site_url('/room') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Rooms
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('/room/store') ?>" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="room_number" class="form-label">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" value="<?= old('room_number') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('room_number')): ?>
                            <div class="text-danger"><?= $validation->getError('room_number') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="room_type" class="form-label">Room Type</label>
                        <select class="form-select" id="room_type" name="room_type" required>
                            <option value="">Select Room Type</option>
                            <option value="Standard" <?= old('room_type') === 'Standard' ? 'selected' : '' ?>>Standard</option>
                            <option value="Deluxe" <?= old('room_type') === 'Deluxe' ? 'selected' : '' ?>>Deluxe</option>
                            <option value="Suite" <?= old('room_type') === 'Suite' ? 'selected' : '' ?>>Suite</option>
                            <option value="Executive" <?= old('room_type') === 'Executive' ? 'selected' : '' ?>>Executive</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('room_type')): ?>
                            <div class="text-danger"><?= $validation->getError('room_type') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price per Night</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price" name="price" value="<?= old('price') ?>" step="0.01" min="0" required>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('price')): ?>
                            <div class="text-danger"><?= $validation->getError('price') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?></textarea>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Save Room</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
