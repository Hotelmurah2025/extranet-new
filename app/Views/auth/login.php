<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('/auth/authenticate') ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Don't have an account? <a href="<?= site_url('/auth/register') ?>">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
