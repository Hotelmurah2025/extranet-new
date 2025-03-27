<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reservation Management</h1>
        <div>
            <a href="<?= site_url('/reservation/calendar') ?>" class="btn btn-info me-2">
                <i class="bi bi-calendar3"></i> Calendar View
            </a>
            <a href="<?= site_url('/reservation/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Reservation
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservations)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No reservations found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= $reservation['room_number'] ?></td>
                                    <td><?= $reservation['guest_name'] ?></td>
                                    <td><?= date('M d, Y', strtotime($reservation['check_in_date'])) ?></td>
                                    <td><?= date('M d, Y', strtotime($reservation['check_out_date'])) ?></td>
                                    <td>
                                        <?php if ($reservation['status'] === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif ($reservation['status'] === 'checked_in'): ?>
                                            <span class="badge bg-success">Checked In</span>
                                        <?php elseif ($reservation['status'] === 'checked_out'): ?>
                                            <span class="badge bg-secondary">Checked Out</span>
                                        <?php elseif ($reservation['status'] === 'cancelled'): ?>
                                            <span class="badge bg-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>$<?= number_format($reservation['total_price'], 2) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= site_url('/reservation/view/' . $reservation['id']) ?>" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            
                                            <?php if ($reservation['status'] === 'pending'): ?>
                                                <a href="<?= site_url('/reservation/check_in/' . $reservation['id']) ?>" class="btn btn-sm btn-success">
                                                    <i class="bi bi-box-arrow-in-right"></i> Check In
                                                </a>
                                            <?php elseif ($reservation['status'] === 'checked_in'): ?>
                                                <a href="<?= site_url('/reservation/check_out/' . $reservation['id']) ?>" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-box-arrow-right"></i> Check Out
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($reservation['status'] === 'pending'): ?>
                                                <a href="<?= site_url('/reservation/edit/' . $reservation['id']) ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $reservation['id'] ?>">
                                                    <i class="bi bi-x-circle"></i> Cancel
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Cancel Modal -->
                                        <?php if ($reservation['status'] === 'pending'): ?>
                                            <div class="modal fade" id="cancelModal<?= $reservation['id'] ?>" tabindex="-1" aria-labelledby="cancelModalLabel<?= $reservation['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="cancelModalLabel<?= $reservation['id'] ?>">Confirm Cancellation</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to cancel this reservation for <?= $reservation['guest_name'] ?> in Room <?= $reservation['room_number'] ?>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <a href="<?= site_url('/reservation/cancel/' . $reservation['id']) ?>" class="btn btn-danger">Cancel Reservation</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
