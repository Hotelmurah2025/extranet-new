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
                        <div class="col-md-6">
                            <h5>Rating Rata-rata: 
                                <span class="text-warning">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= round($averageRating)): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    (<?= $averageRating ?>)
                                </span>
                            </h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= base_url('review') ?>" class="btn btn-secondary">Kembali ke Daftar Hotel</a>
                        </div>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <div class="alert alert-info">
                            <p>Belum ada ulasan untuk hotel ini.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Tamu</th>
                                        <th>Rating</th>
                                        <th>Komentar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($review['review_date'])) ?></td>
                                            <td><?= $review['guest_name'] ?></td>
                                            <td>
                                                <span class="text-warning">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= $review['rating']): ?>
                                                            <i class="fas fa-star"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </span>
                                            </td>
                                            <td><?= substr($review['comment'], 0, 100) . (strlen($review['comment']) > 100 ? '...' : '') ?></td>
                                            <td>
                                                <a href="<?= base_url('review/detail/' . $review['id']) ?>" class="btn btn-sm btn-info">Detail</a>
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
</div>
<?= $this->endSection() ?>
