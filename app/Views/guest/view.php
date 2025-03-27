<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Guest Details</h1>
        <div>
            <a href="<?= site_url('/guest/edit/' . $guest['id']) ?>" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="<?= site_url('/guest') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Guests
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th style="width: 30%">Name:</th>
                            <td><?= $guest['name'] ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= $guest['email'] ?></td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td><?= $guest['phone'] ?></td>
                        </tr>
                        <tr>
                            <th>ID Number:</th>
                            <td><?= $guest['id_number'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td><?= $guest['address'] ?? 'N/A' ?></td>
                        </tr>
                        <tr>
                            <th>Registered On:</th>
                            <td><?= date('F d, Y', strtotime($guest['created_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Booking History</h5>
                    <a href="<?= site_url('/reservation/create?guest_id=' . $guest['id']) ?>" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle"></i> New Reservation
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($bookingHistory)): ?>
                        <p class="text-muted">No booking history found for this guest.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookingHistory as $booking): ?>
                                        <tr>
                                            <td><?= $booking['room_number'] ?></td>
                                            <td><?= date('M d, Y', strtotime($booking['check_in_date'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($booking['check_out_date'])) ?></td>
                                            <td>
                                                <?php if ($booking['status'] === 'pending'): ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif ($booking['status'] === 'checked_in'): ?>
                                                    <span class="badge bg-success">Checked In</span>
                                                <?php elseif ($booking['status'] === 'checked_out'): ?>
                                                    <span class="badge bg-secondary">Checked Out</span>
                                                <?php elseif ($booking['status'] === 'cancelled'): ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('/reservation/view/' . $booking['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
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
</div>
<?= $this->endSection() ?>
