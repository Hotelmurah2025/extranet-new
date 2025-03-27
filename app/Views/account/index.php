<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4><?= $title ?></h4>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Informasi Profil</h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('account/updateProfile') ?>" method="post">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('username')): ?>
                                                <div class="text-danger">
                                                    <?= session()->getFlashdata('validation')->getError('username') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('email')): ?>
                                                <div class="text-danger">
                                                    <?= session()->getFlashdata('validation')->getError('email') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                            <input type="text" class="form-control" id="role" value="<?= $user['role'] === 'admin_pusat' ? 'Admin Pusat' : 'User Hotel' ?>" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Ubah Password</h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('account/changePassword') ?>" method="post">
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Password Saat Ini</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('current_password')): ?>
                                                <div class="text-danger">
                                                    <?= session()->getFlashdata('validation')->getError('current_password') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('new_password')): ?>
                                                <div class="text-danger">
                                                    <?= session()->getFlashdata('validation')->getError('new_password') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                            <?php if (session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('confirm_password')): ?>
                                                <div class="text-danger">
                                                    <?= session()->getFlashdata('validation')->getError('confirm_password') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="<?= base_url('account/viewHotels') ?>" class="btn btn-info">Lihat Hotel Saya</a>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
