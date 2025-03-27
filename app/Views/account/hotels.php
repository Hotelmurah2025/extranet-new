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
                            <a href="<?= base_url('account') ?>" class="btn btn-secondary">Kembali ke Pengaturan Akun</a>
                        </div>
                    </div>
                    
                    <?php if (empty($hotels)): ?>
                        <div class="alert alert-info">
                            <p>Anda belum memiliki akses ke hotel manapun. Silakan hubungi administrator untuk mendapatkan akses.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($hotels as $hotel): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $hotel['name'] ?></h5>
                                            <p class="card-text">
                                                <strong>Alamat:</strong> <?= $hotel['address'] ?>
                                            </p>
                                            <div class="mt-3">
                                                <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-info">Detail Hotel</a>
                                                <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-primary">Lihat Kamar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
