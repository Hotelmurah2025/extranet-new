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
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <a href="<?= base_url('review/hotelReviews/' . $hotel['id']) ?>" class="btn btn-secondary">Kembali ke Daftar Ulasan</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Ulasan dari <?= $review['guest_name'] ?></h5>
                                    <p class="card-text">
                                        <strong>Hotel:</strong> <?= $hotel['name'] ?>
                                    </p>
                                    <p class="card-text">
                                        <strong>Tanggal Ulasan:</strong> <?= date('d M Y', strtotime($review['review_date'])) ?>
                                    </p>
                                    <p class="card-text">
                                        <strong>Rating:</strong> 
                                        <span class="text-warning">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $review['rating']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            (<?= $review['rating'] ?>)
                                        </span>
                                    </p>
                                    <div class="card-text mt-4">
                                        <strong>Komentar:</strong>
                                        <div class="p-3 bg-light rounded mt-2">
                                            <?= nl2br($review['comment']) ?>
                                        </div>
                                    </div>
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
