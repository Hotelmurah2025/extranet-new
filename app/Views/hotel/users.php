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
                    
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-secondary">Kembali ke Detail Hotel</a>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Pengguna yang Memiliki Akses ke Hotel Ini</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($users)): ?>
                                        <div class="alert alert-info">
                                            <p>Belum ada pengguna yang memiliki akses ke hotel ini.</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($users as $user): ?>
                                                        <tr>
                                                            <td><?= $user['username'] ?></td>
                                                            <td><?= $user['email'] ?></td>
                                                            <td><?= $user['role'] === 'admin_pusat' ? 'Admin Pusat' : 'User Hotel' ?></td>
                                                            <td>
                                                                <form action="<?= base_url('hotel/removeUser') ?>" method="post" class="d-inline">
                                                                    <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                                                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus akses pengguna ini?');">
                                                                        Hapus Akses
                                                                    </button>
                                                                </form>
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
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Tambah Pengguna ke Hotel</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($allUsers)): ?>
                                        <div class="alert alert-info">
                                            <p>Tidak ada pengguna hotel yang tersedia untuk ditambahkan.</p>
                                        </div>
                                    <?php else: ?>
                                        <form action="<?= base_url('hotel/addUser') ?>" method="post">
                                            <input type="hidden" name="hotel_id" value="<?= $hotel['id'] ?>">
                                            <div class="mb-3">
                                                <label for="user_id" class="form-label">Pilih Pengguna</label>
                                                <select class="form-select" id="user_id" name="user_id" required>
                                                    <option value="">-- Pilih Pengguna --</option>
                                                    <?php foreach ($allUsers as $user): ?>
                                                        <?php 
                                                        $hasAccess = false;
                                                        foreach ($users as $existingUser) {
                                                            if ($existingUser['id'] === $user['id']) {
                                                                $hasAccess = true;
                                                                break;
                                                            }
                                                        }
                                                        if ($hasAccess) continue;
                                                        ?>
                                                        <option value="<?= $user['id'] ?>"><?= $user['username'] ?> (<?= $user['email'] ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">Tambah Pengguna</button>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
