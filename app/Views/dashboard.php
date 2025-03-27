<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>
    
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
    
    <?php if (isset($noHotelAccess) && $noHotelAccess): ?>
        <div class="alert alert-warning">
            <p>Anda tidak memiliki akses ke hotel manapun. Silakan hubungi administrator untuk mendapatkan akses.</p>
        </div>
    <?php else: ?>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Booking Bulan Ini</h5>
                        <h2 class="card-text"><?= $totalBookings ?></h2>
                        <p class="card-text"><?= $currentMonth ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Tingkat Hunian</h5>
                        <h2 class="card-text"><?= $averageOccupancyRate ?>%</h2>
                        <div class="progress mt-2" style="height: 10px;">
                            <div class="progress-bar bg-light" role="progressbar" style="width: <?= min($averageOccupancyRate, 100) ?>%;" 
                                aria-valuenow="<?= $averageOccupancyRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Review</h5>
                        <h2 class="card-text">
                            <?= $averageReviewRating ?> 
                            <span class="fs-6">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($averageReviewRating)): ?>
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
        
        <?php if ($role === 'admin_pusat'): ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Daftar Hotel</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 text-end">
                                <a href="<?= base_url('hotel/create') ?>" class="btn btn-primary">Tambah Hotel Baru</a>
                            </div>
                            
                            <?php if (empty($hotels)): ?>
                                <div class="alert alert-info">
                                    <p>Belum ada hotel yang terdaftar.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Hotel</th>
                                                <th>Alamat</th>
                                                <th>Jumlah Kamar</th>
                                                <th>Tingkat Hunian</th>
                                                <th>Rating</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($hotels as $hotel): ?>
                                                <?php $stats = $hotelStats[$hotel['id']] ?? null; ?>
                                                <?php if (!$stats) continue; ?>
                                                <tr>
                                                    <td><?= $hotel['name'] ?></td>
                                                    <td><?= $hotel['address'] ?></td>
                                                    <td><?= $stats['roomCount'] ?></td>
                                                    <td>
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                style="width: <?= min($stats['occupancyRate'], 100) ?>%;" 
                                                                aria-valuenow="<?= $stats['occupancyRate'] ?>" 
                                                                aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small><?= $stats['occupancyRate'] ?>%</small>
                                                    </td>
                                                    <td>
                                                        <span class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= round($stats['averageRating'])): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                        <small>(<?= $stats['averageRating'] ?>)</small>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                                                        <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-sm btn-primary">Kamar</a>
                                                        <a href="<?= base_url('hotel/users/' . $hotel['id']) ?>" class="btn btn-sm btn-secondary">Pengguna</a>
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
        <?php else: ?>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Hotel Saya</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($hotels)): ?>
                                <div class="alert alert-info">
                                    <p>Anda belum memiliki akses ke hotel manapun.</p>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($hotels as $hotel): ?>
                                        <?php $stats = $hotelStats[$hotel['id']] ?? null; ?>
                                        <?php if (!$stats) continue; ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5><?= $hotel['name'] ?></h5>
                                                </div>
                                                <div class="card-body">
                                                    <p><strong>Alamat:</strong> <?= $hotel['address'] ?></p>
                                                    <p><strong>Jumlah Kamar:</strong> <?= $stats['roomCount'] ?></p>
                                                    <p><strong>Booking Bulan Ini:</strong> <?= $stats['bookings'] ?></p>
                                                    <p>
                                                        <strong>Tingkat Hunian:</strong> 
                                                        <div class="progress" style="height: 10px;">
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                style="width: <?= min($stats['occupancyRate'], 100) ?>%;" 
                                                                aria-valuenow="<?= $stats['occupancyRate'] ?>" 
                                                                aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small><?= $stats['occupancyRate'] ?>%</small>
                                                    </p>
                                                    <p>
                                                        <strong>Rating:</strong>
                                                        <span class="text-warning">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= round($stats['averageRating'])): ?>
                                                                    <i class="fas fa-star"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </span>
                                                        <small>(<?= $stats['averageRating'] ?>)</small>
                                                    </p>
                                                    <div class="mt-3">
                                                        <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-info">Detail Hotel</a>
                                                        <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-primary">Kelola Kamar</a>
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
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Ulasan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        $hasReviews = false;
                        foreach ($hotelStats as $hotelId => $stats): 
                            if (!empty($stats['recentReviews'])):
                                $hasReviews = true;
                                break;
                            endif;
                        endforeach;
                        ?>
                        
                        <?php if (!$hasReviews): ?>
                            <div class="alert alert-info">
                                <p>Belum ada ulasan untuk hotel Anda.</p>
                            </div>
                        <?php else: ?>
                            <div class="accordion" id="reviewAccordion">
                                <?php foreach ($hotelStats as $hotelId => $stats): ?>
                                    <?php if (empty($stats['recentReviews'])) continue; ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading<?= $hotelId ?>">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapse<?= $hotelId ?>" aria-expanded="true" 
                                                aria-controls="collapse<?= $hotelId ?>">
                                                <?= $stats['hotel']['name'] ?> 
                                                <span class="ms-2 text-warning">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= round($stats['averageRating'])): ?>
                                                            <i class="fas fa-star"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </span>
                                            </button>
                                        </h2>
                                        <div id="collapse<?= $hotelId ?>" class="accordion-collapse collapse" 
                                            aria-labelledby="heading<?= $hotelId ?>" data-bs-parent="#reviewAccordion">
                                            <div class="accordion-body">
                                                <?php foreach ($stats['recentReviews'] as $review): ?>
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
                                                                <?= substr($review['comment'], 0, 150) . (strlen($review['comment']) > 150 ? '...' : '') ?>
                                                            </p>
                                                            <a href="<?= base_url('review/detail/' . $review['id']) ?>" class="btn btn-sm btn-outline-info">Lihat Detail</a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="text-end mt-2">
                                                    <a href="<?= base_url('review/hotelReviews/' . $hotelId) ?>" class="btn btn-sm btn-info">Lihat Semua Ulasan</a>
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
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="<?= base_url('account') ?>" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fas fa-user-cog fa-2x mb-2"></i><br>
                                    Pengaturan Akun
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="<?= base_url('review') ?>" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fas fa-star fa-2x mb-2"></i><br>
                                    Lihat Ulasan
                                </a>
                            </div>
                            <?php if ($role === 'admin_pusat'): ?>
                                <div class="col-md-6 mb-3">
                                    <a href="<?= base_url('hotel/create') ?>" class="btn btn-outline-success w-100 py-3">
                                        <i class="fas fa-hotel fa-2x mb-2"></i><br>
                                        Tambah Hotel
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="<?= base_url('hotel') ?>" class="btn btn-outline-info w-100 py-3">
                                        <i class="fas fa-list fa-2x mb-2"></i><br>
                                        Kelola Hotel
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6 mb-3">
                                    <a href="<?= base_url('room/create') ?>" class="btn btn-outline-success w-100 py-3">
                                        <i class="fas fa-door-open fa-2x mb-2"></i><br>
                                        Tambah Kamar
                                    </a>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="<?= base_url('room') ?>" class="btn btn-outline-info w-100 py-3">
                                        <i class="fas fa-list fa-2x mb-2"></i><br>
                                        Kelola Kamar
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
