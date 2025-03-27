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
                            <?php if (session()->get('role') === 'admin_pusat'): ?>
                                <a href="<?= base_url('hotel/edit/' . $hotel['id']) ?>" class="btn btn-warning">Edit Hotel</a>
                                <a href="<?= base_url('hotel/users/' . $hotel['id']) ?>" class="btn btn-secondary">Kelola Pengguna</a>
                            <?php endif; ?>
                            <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-primary">Kelola Kamar</a>
                            <a href="<?= base_url('review/hotelReviews/' . $hotel['id']) ?>" class="btn btn-info">Lihat Ulasan</a>
                            <a href="<?= base_url('hotel') ?>" class="btn btn-secondary">Kembali ke Daftar Hotel</a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Hotel</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Nama Hotel</div>
                                        <div class="col-md-8"><?= $hotel['name'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Alamat</div>
                                        <div class="col-md-8"><?= $hotel['address'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Lokasi Map</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($hotel['location'])): ?>
                                                <?= $hotel['location'] ?>
                                                <div class="mt-2">
                                                    <a href="https://maps.google.com/?q=<?= $hotel['location'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marker-alt"></i> Lihat di Google Maps
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Deskripsi</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($hotel['description'])): ?>
                                                <?= nl2br($hotel['description']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Fasilitas Umum</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($hotel['facilities'])): ?>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach (explode(',', $hotel['facilities']) as $facility): ?>
                                                        <li class="list-group-item"><?= trim($facility) ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title">Statistik Hotel</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Jumlah Kamar</h6>
                                                    <h2 class="card-text"><?= $roomCount ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-success text-white">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Tingkat Hunian</h6>
                                                    <h2 class="card-text"><?= $occupancyRate ?>%</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-warning text-dark">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Rating</h6>
                                                    <h2 class="card-text">
                                                        <?= $averageRating ?>
                                                        <span class="fs-6">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= round($averageRating)): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                    </h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Foto Hotel</h5>
                                    <?php if (empty($images)): ?>
                                        <div class="alert alert-info">
                                            <p>Belum ada foto untuk hotel ini.</p>
                                        </div>
                                    <?php else: ?>
                                        <div id="hotelCarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <?php foreach ($images as $index => $image): ?>
                                                    <button type="button" data-bs-target="#hotelCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="carousel-inner">
                                                <?php foreach ($images as $index => $image): ?>
                                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                        <img src="<?= base_url($image['image_path']) ?>" class="d-block w-100" alt="Hotel Image" style="height: 300px; object-fit: cover;">
                                                        <?php if ($image['is_primary']): ?>
                                                            <div class="carousel-caption d-none d-md-block">
                                                                <span class="badge bg-primary">Foto Utama</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Ulasan Terbaru</h5>
                                    <?php if (empty($reviews)): ?>
                                        <div class="alert alert-info">
                                            <p>Belum ada ulasan untuk hotel ini.</p>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach (array_slice($reviews, 0, 3) as $review): ?>
                                            <div class="card mb-2">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between">
                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                            <?= $review['guest_name'] ?> - 
                                                            <?= date('d M Y', strtotime($review['review_date'])) ?>
                                                        </h6>
                                                        <span class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= $review['rating']): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                    </div>
                                                    <p class="card-text">
                                                        <?= substr($review['comment'], 0, 100) . (strlen($review['comment']) > 100 ? '...' : '') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="text-end mt-2">
                                            <a href="<?= base_url('review/hotelReviews/' . $hotel['id']) ?>" class="btn btn-sm btn-outline-info">Lihat Semua Ulasan</a>
                                        </div>
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
