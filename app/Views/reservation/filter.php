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
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Filter Reservasi</h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('reservation/filter') ?>" method="get" id="filterForm">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="hotel_id" class="form-label">Hotel</label>
                                                <select class="form-select" id="hotel_id" name="hotel_id">
                                                    <option value="">Semua Hotel</option>
                                                    <?php foreach ($hotels as $hotel): ?>
                                                        <option value="<?= $hotel['id'] ?>" <?= ($filters['hotel_id'] ?? '') == $hotel['id'] ? 'selected' : '' ?>>
                                                            <?= $hotel['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="start_date" class="form-label">Check-in Dari</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $filters['start_date'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="end_date" class="form-label">Check-in Sampai</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $filters['end_date'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="status" class="form-label">Status Reservasi</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="">Semua Status</option>
                                                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                    <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                    <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="payment_status" class="form-label">Status Pembayaran</label>
                                                <select class="form-select" id="payment_status" name="payment_status">
                                                    <option value="">Semua Status</option>
                                                    <option value="paid" <?= ($filters['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="pending" <?= ($filters['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="unpaid" <?= ($filters['payment_status'] ?? '') === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="guest_name" class="form-label">Nama Tamu</label>
                                                <input type="text" class="form-control" id="guest_name" name="guest_name" value="<?= $filters['guest_name'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="booking_code" class="form-label">Kode Booking</label>
                                                <input type="text" class="form-control" id="booking_code" name="booking_code" value="<?= $filters['booking_code'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="source" class="form-label">Sumber Reservasi</label>
                                                <select class="form-select" id="source" name="source">
                                                    <option value="">Semua Sumber</option>
                                                    <option value="direct" <?= ($filters['source'] ?? '') === 'direct' ? 'selected' : '' ?>>Direct</option>
                                                    <option value="hotelmurah" <?= ($filters['source'] ?? '') === 'hotelmurah' ? 'selected' : '' ?>>HotelMurah.com</option>
                                                    <option value="phone" <?= ($filters['source'] ?? '') === 'phone' ? 'selected' : '' ?>>Phone</option>
                                                    <option value="email" <?= ($filters['source'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                                    <option value="other" <?= ($filters['source'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 text-end">
                                                <button type="reset" class="btn btn-secondary" id="resetFilter">Reset Filter</button>
                                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Hasil Filter: <?= count($reservations) ?> reservasi ditemukan</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group">
                                <a href="<?= base_url('reservation') ?>" class="btn btn-secondary">Kembali ke Daftar Reservasi</a>
                                <?php if (session()->get('role') === 'admin_pusat'): ?>
                                    <a href="<?= base_url('reservation/create') ?>" class="btn btn-success">Tambah Reservasi</a>
                                    <a href="<?= base_url('reservation/export?' . http_build_query($filters)) ?>" class="btn btn-info">Export Excel</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (empty($reservations)): ?>
                        <div class="alert alert-info">
                            <p>Tidak ada reservasi yang ditemukan dengan filter yang dipilih.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Booking</th>
                                        <th>Hotel</th>
                                        <th>Kamar</th>
                                        <th>Nama Tamu</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Sumber</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <tr>
                                            <td><?= $reservation['booking_code'] ?></td>
                                            <td><?= $reservation['hotel_name'] ?></td>
                                            <td><?= $reservation['room_name'] ?></td>
                                            <td><?= $reservation['guest_name'] ?></td>
                                            <td><?= date('d M Y', strtotime($reservation['check_in_date'])) ?></td>
                                            <td><?= date('d M Y', strtotime($reservation['check_out_date'])) ?></td>
                                            <td>
                                                <?php if ($reservation['status'] === 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif ($reservation['status'] === 'confirmed'): ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php elseif ($reservation['status'] === 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php elseif ($reservation['status'] === 'completed'): ?>
                                                    <span class="badge bg-info">Completed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($reservation['status']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($reservation['payment_status'] === 'paid'): ?>
                                                    <span class="badge bg-success">Paid</span>
                                                <?php elseif ($reservation['payment_status'] === 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Unpaid</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= ucfirst($reservation['source'] ?? 'Direct') ?></td>
                                            <td>
                                                <a href="<?= base_url('reservation/detail/' . $reservation['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                                                <?php if ($reservation['status'] === 'pending'): ?>
                                                    <a href="<?= base_url('reservation/confirm/' . $reservation['id']) ?>" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi reservasi ini?');">Konfirmasi</a>
                                                <?php endif; ?>
                                                <?php if ($reservation['status'] !== 'cancelled' && $reservation['status'] !== 'completed'): ?>
                                                    <a href="<?= base_url('reservation/cancel/' . $reservation['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan reservasi ini?');">Batalkan</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <?= $pager->links() ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resetFilterBtn = document.getElementById('resetFilter');
    
    resetFilterBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        document.getElementById('hotel_id').value = '';
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('status').value = '';
        document.getElementById('payment_status').value = '';
        document.getElementById('guest_name').value = '';
        document.getElementById('booking_code').value = '';
        document.getElementById('source').value = '';
        
        document.getElementById('filterForm').submit();
    });
});
</script>
<?= $this->endSection() ?>
