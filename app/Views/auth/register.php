<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/auth/store') ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="text-danger"><?= $validation->getError('username') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <?php if (isset($validation) && $validation->hasError('email')): ?>
                                <div class="text-danger"><?= $validation->getError('email') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="text-danger"><?= $validation->getError('password') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <?php if (isset($validation) && $validation->hasError('confirm_password')): ?>
                                <div class="text-danger"><?= $validation->getError('confirm_password') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="<?= site_url('/auth/login') ?>">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
