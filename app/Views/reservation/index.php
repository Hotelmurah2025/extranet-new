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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <form action="<?= base_url('reservation') ?>" method="get" class="row g-3">
                                                <div class="col-md-3">
                                                    <input type="date" class="form-control" name="start_date" value="<?= $filters['start_date'] ?? '' ?>" placeholder="Tanggal Mulai">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" class="form-control" name="end_date" value="<?= $filters['end_date'] ?? '' ?>" placeholder="Tanggal Selesai">
                                                </div>
                                                <div class="col-md-3">
                                                    <select class="form-select" name="status">
                                                        <option value="">Semua Status</option>
                                                        <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                                        <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                        <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="<?= base_url('reservation') ?>" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <?php if (session()->get('role') === 'admin_pusat'): ?>
                                                <a href="<?= base_url('reservation/create') ?>" class="btn btn-success">Tambah Reservasi</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (empty($reservations)): ?>
                        <div class="alert alert-info">
                            <p>Tidak ada reservasi yang ditemukan.</p>
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
                                            <td>
                                                <a href="<?= base_url('reservation/view/' . $reservation['id']) ?>" class="btn btn-sm btn-info">Detail</a>
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
<?= $this->endSection() ?>
