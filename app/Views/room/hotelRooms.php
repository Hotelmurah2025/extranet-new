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
                            <a href="<?= base_url('room/create/' . $hotel['id']) ?>" class="btn btn-primary">Tambah Kamar Baru</a>
                            <a href="<?= base_url('hotel/view/' . $hotel['id']) ?>" class="btn btn-secondary">Kembali ke Detail Hotel</a>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" id="roomSearch" class="form-control" placeholder="Cari kamar...">
                                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex justify-content-md-end">
                                                <select id="typeFilter" class="form-select" style="width: auto;">
                                                    <option value="">Semua Tipe</option>
                                                    <?php foreach ($roomTypes as $type): ?>
                                                        <option value="<?= $type ?>"><?= $type ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (empty($rooms)): ?>
                        <div class="alert alert-info">
                            <p>Belum ada kamar yang terdaftar untuk hotel ini.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($rooms as $room): ?>
                                <div class="col-md-4 mb-4 room-card" data-room-type="<?= $room['room_type'] ?>">
                                    <div class="card h-100">
                                        <?php if (!empty($room['image_path'])): ?>
                                            <img src="<?= base_url($room['image_path']) ?>" class="card-img-top" alt="<?= $room['name'] ?>" style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                <i class="fas fa-bed fa-3x text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $room['name'] ?></h5>
                                            <p class="card-text">
                                                <strong>Tipe:</strong> <?= $room['room_type'] ?><br>
                                                <strong>Kapasitas:</strong> <?= $room['capacity'] ?> orang<br>
                                                <strong>Harga Dasar:</strong> Rp <?= number_format($room['base_price'], 0, ',', '.') ?> / malam
                                            </p>
                                            <div class="mt-3">
                                                <a href="<?= base_url('room/view/' . $room['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                                                <a href="<?= base_url('room/edit/' . $room['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= base_url('room/calendar/' . $room['id']) ?>" class="btn btn-sm btn-primary">Kalender</a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $room['id'] ?>">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?= $room['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $room['id'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel<?= $room['id'] ?>">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus kamar "<?= $room['name'] ?>"?</p>
                                                    <p class="text-danger">Perhatian: Semua data terkait kamar ini (reservasi, harga, dll) juga akan dihapus.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <a href="<?= base_url('room/delete/' . $room['id']) ?>" class="btn btn-danger">Hapus</a>
                                                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSearch = document.getElementById('roomSearch');
    const searchButton = document.getElementById('searchButton');
    const typeFilter = document.getElementById('typeFilter');
    const roomCards = document.querySelectorAll('.room-card');
    
    function filterRooms() {
        const searchTerm = roomSearch.value.toLowerCase();
        const typeValue = typeFilter.value;
        
        roomCards.forEach(card => {
            const roomName = card.querySelector('.card-title').textContent.toLowerCase();
            const roomType = card.dataset.roomType;
            const roomInfo = card.querySelector('.card-text').textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || 
                                roomName.includes(searchTerm) || 
                                roomInfo.includes(searchTerm);
            const matchesType = typeValue === '' || roomType === typeValue;
            
            card.style.display = (matchesSearch && matchesType) ? '' : 'none';
        });
    }
    
    searchButton.addEventListener('click', filterRooms);
    roomSearch.addEventListener('keyup', filterRooms);
    typeFilter.addEventListener('change', filterRooms);
});
</script>
<?= $this->endSection() ?>
