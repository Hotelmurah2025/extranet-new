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
                                        <th>Lokasi</th>
                                        <th>Jumlah Kamar</th>
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hotels as $hotel): ?>
                                        <tr>
                                            <td><?= $hotel['name'] ?></td>
                                            <td><?= $hotel['address'] ?></td>
                                            <td><?= $hotel['location'] ?></td>
                                            <td><?= $hotel['room_count'] ?? 0 ?></td>
                                            <td>
                                                <span class="text-warning">
                                                    <?php 
                                                    $rating = $hotel['average_rating'] ?? 0;
                                                    for ($i = 1; $i <= 5; $i++): 
                                                    ?>
                                                        <?php if ($i <= round($rating)): ?>
                                                            <i class="fas fa-star"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </span>
                                                <small>(<?= number_format($rating, 1) ?>)</small>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                                                <a href="<?= base_url('hotel/edit/' . $hotel['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-sm btn-primary">Kamar</a>
                                                <a href="<?= base_url('hotel/users/' . $hotel['id']) ?>" class="btn btn-sm btn-secondary">Pengguna</a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $hotel['id'] ?>">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $hotel['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $hotel['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $hotel['id'] ?>">Konfirmasi Hapus</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus hotel "<?= $hotel['name'] ?>"?</p>
                                                        <p class="text-danger">Perhatian: Semua data terkait hotel ini (kamar, reservasi, dll) juga akan dihapus.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?= base_url('hotel/delete/' . $hotel['id']) ?>" class="btn btn-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
