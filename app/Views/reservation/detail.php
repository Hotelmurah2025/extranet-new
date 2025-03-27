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
                            <a href="<?= base_url('reservation') ?>" class="btn btn-secondary">Kembali ke Daftar Reservasi</a>
                            
                            <?php if ($reservation['status'] === 'pending'): ?>
                                <a href="<?= base_url('reservation/confirm/' . $reservation['id']) ?>" class="btn btn-success" onclick="return confirm('Konfirmasi reservasi ini?');">Konfirmasi Reservasi</a>
                            <?php endif; ?>
                            
                            <?php if ($reservation['status'] !== 'cancelled' && $reservation['status'] !== 'completed'): ?>
                                <a href="<?= base_url('reservation/cancel/' . $reservation['id']) ?>" class="btn btn-danger" onclick="return confirm('Batalkan reservasi ini?');">Batalkan Reservasi</a>
                            <?php endif; ?>
                            
                            <?php if ($reservation['status'] === 'confirmed' && $reservation['payment_status'] !== 'paid'): ?>
                                <a href="<?= base_url('reservation/markAsPaid/' . $reservation['id']) ?>" class="btn btn-primary" onclick="return confirm('Tandai reservasi ini sebagai sudah dibayar?');">Tandai Sudah Dibayar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Informasi Reservasi</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">ID Booking</div>
                                        <div class="col-md-8"><?= $reservation['booking_code'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Tanggal Pemesanan</div>
                                        <div class="col-md-8"><?= date('d M Y H:i', strtotime($reservation['created_at'])) ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Status</div>
                                        <div class="col-md-8">
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
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Status Pembayaran</div>
                                        <div class="col-md-8">
                                            <?php if ($reservation['payment_status'] === 'paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php elseif ($reservation['payment_status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Unpaid</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Metode Pembayaran</div>
                                        <div class="col-md-8"><?= $reservation['payment_method'] ?? 'Belum ditentukan' ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Sumber Reservasi</div>
                                        <div class="col-md-8"><?= $reservation['source'] ?? 'Direct' ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Catatan</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($reservation['notes'])): ?>
                                                <?= nl2br($reservation['notes']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak ada catatan</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Informasi Tamu</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Nama Tamu</div>
                                        <div class="col-md-8"><?= $reservation['guest_name'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Email</div>
                                        <div class="col-md-8"><?= $reservation['guest_email'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Telepon</div>
                                        <div class="col-md-8"><?= $reservation['guest_phone'] ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Alamat</div>
                                        <div class="col-md-8">
                                            <?php if (!empty($reservation['guest_address'])): ?>
                                                <?= nl2br($reservation['guest_address']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Informasi Menginap</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Check-in</div>
                                        <div class="col-md-8"><?= date('d M Y', strtotime($reservation['check_in_date'])) ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Check-out</div>
                                        <div class="col-md-8"><?= date('d M Y', strtotime($reservation['check_out_date'])) ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Durasi</div>
                                        <div class="col-md-8"><?= $nights ?> malam</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4 fw-bold">Jumlah Tamu</div>
                                        <div class="col-md-8"><?= $reservation['num_guests'] ?> orang</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Detail Kamar & Biaya</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hotel</th>
                                                    <th>Kamar</th>
                                                    <th>Tipe Kamar</th>
                                                    <th>Harga per Malam</th>
                                                    <th>Jumlah Malam</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?= $hotel['name'] ?></td>
                                                    <td><?= $room['name'] ?></td>
                                                    <td><?= $room['room_type'] ?></td>
                                                    <td>Rp <?= number_format($reservation['room_rate'], 0, ',', '.') ?></td>
                                                    <td><?= $nights ?></td>
                                                    <td>Rp <?= number_format($reservation['room_rate'] * $nights, 0, ',', '.') ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-6 offset-md-6">
                                            <table class="table">
                                                <tr>
                                                    <td class="fw-bold">Subtotal</td>
                                                    <td class="text-end">Rp <?= number_format($reservation['room_rate'] * $nights, 0, ',', '.') ?></td>
                                                </tr>
                                                <?php if (!empty($reservation['tax_amount'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Pajak</td>
                                                        <td class="text-end">Rp <?= number_format($reservation['tax_amount'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($reservation['additional_charges'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Biaya Tambahan</td>
                                                        <td class="text-end">Rp <?= number_format($reservation['additional_charges'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <?php if (!empty($reservation['discount_amount'])): ?>
                                                    <tr>
                                                        <td class="fw-bold">Diskon</td>
                                                        <td class="text-end">- Rp <?= number_format($reservation['discount_amount'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <tr class="table-primary">
                                                    <td class="fw-bold">Total</td>
                                                    <td class="text-end fw-bold">Rp <?= number_format($reservation['total_amount'], 0, ',', '.') ?></td>
                                                </tr>
                                            </table>
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
