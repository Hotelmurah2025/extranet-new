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
                    <?php if (isset($noHotelAccess) && $noHotelAccess): ?>
                        <div class="alert alert-warning">
                            <p>Anda tidak memiliki akses ke hotel manapun. Silakan hubungi administrator untuk mendapatkan akses.</p>
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
                                            <a href="<?= base_url('review/hotelReviews/' . $hotel['id']) ?>" class="btn btn-primary">Lihat Ulasan</a>
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
