<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reservation Details</h1>
        <div>
            <?php if ($reservation['status'] === 'pending'): ?>
                <a href="<?= site_url('/reservation/check_in/' . $reservation['id']) ?>" class="btn btn-success me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Check In
                </a>
                <a href="<?= site_url('/reservation/edit/' . $reservation['id']) ?>" class="btn btn-primary me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            <?php elseif ($reservation['status'] === 'checked_in'): ?>
                <a href="<?= site_url('/reservation/check_out/' . $reservation['id']) ?>" class="btn btn-warning me-2">
                    <i class="bi bi-box-arrow-right"></i> Check Out
                </a>
            <?php endif; ?>
            <a href="<?= site_url('/reservation') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Reservations
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Reservation Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">Room:</th>
                            <td><?= $reservation['room_number'] ?> (<?= $reservation['room_type'] ?>)</td>
                        </tr>
                        <tr>
                            <th>Check-in Date:</th>
                            <td><?= date('F d, Y', strtotime($reservation['check_in_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Check-out Date:</th>
                            <td><?= date('F d, Y', strtotime($reservation['check_out_date'])) ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
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
                        </tr>
                        <tr>
                            <th>Total Price:</th>
                            <td>$<?= number_format($reservation['total_price'], 2) ?></td>
                        </tr>
                        <tr>
                            <th>Created On:</th>
                            <td><?= date('F d, Y H:i', strtotime($reservation['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Notes:</th>
                            <td><?= $reservation['notes'] ?? 'N/A' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Guest Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <td><?= $reservation['guest_name'] ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= $reservation['email'] ?></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?= $reservation['phone'] ?></td>
                        </tr>
                    </table>
                    
                    <div class="d-grid gap-2 mt-3">
                        <a href="<?= site_url('/guest/view/' . $reservation['guest_id']) ?>" class="btn btn-info">
                            <i class="bi bi-person"></i> View Guest Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Confirm Cancellation</h5>
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
<?= $this->endSection() ?>
