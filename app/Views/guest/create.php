<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Add New Guest</h1>
        <a href="<?= site_url('/guest') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Guests
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('/guest/store') ?>" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('name')): ?>
                            <div class="text-danger"><?= $validation->getError('name') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <div class="text-danger"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('phone')): ?>
                            <div class="text-danger"><?= $validation->getError('phone') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input type="text" class="form-control" id="id_number" name="id_number" value="<?= old('id_number') ?>">
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Save Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
