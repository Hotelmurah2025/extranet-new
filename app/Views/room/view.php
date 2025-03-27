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
                        <div class="col-md-6">
                            <h5>Hotel: <?= $hotel['name'] ?></h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= base_url('room/edit/' . $room['id']) ?>" class="btn btn-warning">Edit Kamar</a>
                            <a href="<?= base_url('room/calendar/' . $room['id']) ?>" class="btn btn-primary">Kalender Ketersediaan</a>
                            <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-secondary">Kembali ke Daftar Kamar</a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Kamar</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Nama Kamar</div>
                                        <div class="col-md-8"><?= $room['name'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Tipe Kamar</div>
                                        <div class="col-md-8"><?= $room['room_type'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Kapasitas</div>
                                        <div class="col-md-8"><?= $room['capacity'] ?> orang</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Harga Dasar</div>
                                        <div class="col-md-8">Rp <?= number_format($room['base_price'], 0, ',', '.') ?> / malam</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Deskripsi</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($room['description'])): ?>
                                                <?= nl2br($room['description']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Fasilitas Kamar</h5>
                                    <?php if (!empty($room['facilities'])): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach (explode(',', $room['facilities']) as $facility): ?>
                                                <li class="list-group-item"><?= trim($facility) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="alert alert-info">
                                            <p>Belum ada fasilitas yang ditambahkan untuk kamar ini.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Statistik Kamar</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Tingkat Hunian</h6>
                                                    <h2 class="card-text"><?= $occupancyRate ?>%</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-success text-white">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Pendapatan Bulan Ini</h6>
                                                    <h2 class="card-text">Rp <?= number_format($monthlyRevenue, 0, ',', '.') ?></h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-info text-white">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">Reservasi Bulan Ini</h6>
                                                    <h2 class="card-text"><?= $monthlyBookings ?></h2>
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
                                    <h5 class="card-title">Foto Kamar</h5>
                                    <?php if (empty($images)): ?>
                                        <div class="alert alert-info">
                                            <p>Belum ada foto untuk kamar ini.</p>
                                        </div>
                                    <?php else: ?>
                                        <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <?php foreach ($images as $index => $image): ?>
                                                    <button type="button" data-bs-target="#roomCarousel" data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active"' : '' ?> aria-label="Slide <?= $index + 1 ?>"></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="carousel-inner">
                                                <?php foreach ($images as $index => $image): ?>
                                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                                        <img src="<?= base_url($image['image_path']) ?>" class="d-block w-100" alt="Room Image" style="height: 300px; object-fit: cover;">
                                                        <?php if ($image['is_primary']): ?>
                                                            <div class="carousel-caption d-none d-md-block">
                                                                <span class="badge bg-primary">Foto Utama</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Ketersediaan Mendatang</h5>
                                    <div class="list-group">
                                        <?php if (empty($upcomingAvailability)): ?>
                                            <div class="alert alert-info">
                                                <p>Tidak ada data ketersediaan mendatang.</p>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($upcomingAvailability as $date => $status): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= date('d M Y', strtotime($date)) ?>
                                                    <?php if ($status['is_available']): ?>
                                                        <span class="badge bg-success">Tersedia</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Tidak Tersedia</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="text-center mt-3">
                                                <a href="<?= base_url('room/calendar/' . $room['id']) ?>" class="btn btn-sm btn-outline-primary">Lihat Kalender Lengkap</a>
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
</div>
<?= $this->endSection() ?>
