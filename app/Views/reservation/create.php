<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create Reservation</h1>
        <a href="<?= site_url('/reservation') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Reservations
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="<?= site_url('/reservation/store') ?>" method="post" id="reservationForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="guest_id" class="form-label">Guest</label>
                        <select class="form-select" id="guest_id" name="guest_id" required>
                            <option value="">Select Guest</option>
                            <?php foreach ($guests as $guest): ?>
                                <option value="<?= $guest['id'] ?>" <?= old('guest_id') == $guest['id'] ? 'selected' : '' ?>>
                                    <?= $guest['name'] ?> (<?= $guest['email'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('guest_id')): ?>
                            <div class="text-danger"><?= $validation->getError('guest_id') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="room_id" class="form-label">Room</label>
                        <select class="form-select" id="room_id" name="room_id" required>
                            <option value="">Select Room</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?= $room['id'] ?>" data-price="<?= $room['price'] ?>" <?= old('room_id') == $room['id'] ? 'selected' : '' ?>>
                                    <?= $room['room_number'] ?> - <?= $room['room_type'] ?> ($<?= number_format($room['price'], 2) ?>/night)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('room_id')): ?>
                            <div class="text-danger"><?= $validation->getError('room_id') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="check_in_date" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="check_in_date" name="check_in_date" value="<?= old('check_in_date', date('Y-m-d')) ?>" min="<?= date('Y-m-d') ?>" required>
                        <?php if (isset($validation) && $validation->hasError('check_in_date')): ?>
                            <div class="text-danger"><?= $validation->getError('check_in_date') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="check_out_date" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" value="<?= old('check_out_date', date('Y-m-d', strtotime('+1 day'))) ?>" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        <?php if (isset($validation) && $validation->hasError('check_out_date')): ?>
                            <div class="text-danger"><?= $validation->getError('check_out_date') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Reservation Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Room:</strong> <span id="summaryRoom">Not selected</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Check-in:</strong> <span id="summaryCheckIn">Not selected</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Check-out:</strong> <span id="summaryCheckOut">Not selected</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Nights:</strong> <span id="summaryNights">0</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Price per Night:</strong> $<span id="summaryPrice">0.00</span>
                                </div>
                                <div class="mb-3">
                                    <strong>Total Price:</strong> $<span id="summaryTotal">0.00</span>
                                </div>
                                <div id="availabilityMessage" class="alert alert-info d-none">
                                    Checking room availability...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Create Reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const summaryRoom = document.getElementById('summaryRoom');
    const summaryCheckIn = document.getElementById('summaryCheckIn');
    const summaryCheckOut = document.getElementById('summaryCheckOut');
    const summaryNights = document.getElementById('summaryNights');
    const summaryPrice = document.getElementById('summaryPrice');
    const summaryTotal = document.getElementById('summaryTotal');
    const availabilityMessage = document.getElementById('availabilityMessage');
    const submitBtn = document.getElementById('submitBtn');
    
    function updateSummary() {
        if (roomSelect.value) {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const roomText = selectedOption.text.split(' - ')[0] + ' - ' + selectedOption.text.split(' - ')[1];
            summaryRoom.textContent = roomText;
            
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            summaryPrice.textContent = price.toFixed(2);
            
            if (checkInDate.value && checkOutDate.value) {
                const checkIn = new Date(checkInDate.value);
                const checkOut = new Date(checkOutDate.value);
                
                summaryCheckIn.textContent = checkIn.toLocaleDateString();
                summaryCheckOut.textContent = checkOut.toLocaleDateString();
                
                const timeDiff = checkOut.getTime() - checkIn.getTime();
                const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                
                if (nights > 0) {
                    summaryNights.textContent = nights;
                    const total = price * nights;
                    summaryTotal.textContent = total.toFixed(2);
                    
                    checkAvailability();
                } else {
                    summaryNights.textContent = '0';
                    summaryTotal.textContent = '0.00';
                    availabilityMessage.textContent = 'Check-out date must be after check-in date';
                    availabilityMessage.classList.remove('d-none', 'alert-success', 'alert-danger');
                    availabilityMessage.classList.add('alert-warning');
                    submitBtn.disabled = true;
                }
            }
        } else {
            summaryRoom.textContent = 'Not selected';
            summaryPrice.textContent = '0.00';
            summaryTotal.textContent = '0.00';
        }
    }
    
    function checkAvailability() {
        if (!roomSelect.value || !checkInDate.value || !checkOutDate.value) {
            return;
        }
        
        availabilityMessage.textContent = 'Checking room availability...';
        availabilityMessage.classList.remove('d-none', 'alert-success', 'alert-danger', 'alert-warning');
        availabilityMessage.classList.add('alert-info');
        
        const url = `<?= site_url('/reservation/check_availability') ?>?room_id=${roomSelect.value}&check_in_date=${checkInDate.value}&check_out_date=${checkOutDate.value}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    availabilityMessage.textContent = 'Room is available for the selected dates!';
                    availabilityMessage.classList.remove('alert-info', 'alert-danger', 'alert-warning');
                    availabilityMessage.classList.add('alert-success');
                    submitBtn.disabled = false;
                } else {
                    availabilityMessage.textContent = 'Room is not available for the selected dates. Please choose different dates or room.';
                    availabilityMessage.classList.remove('alert-info', 'alert-success', 'alert-warning');
                    availabilityMessage.classList.add('alert-danger');
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error checking availability:', error);
                availabilityMessage.textContent = 'Error checking availability. Please try again.';
                availabilityMessage.classList.remove('alert-info', 'alert-success', 'alert-warning');
                availabilityMessage.classList.add('alert-danger');
            });
    }
    
    roomSelect.addEventListener('change', updateSummary);
    checkInDate.addEventListener('change', function() {
        if (checkInDate.value) {
            const nextDay = new Date(checkInDate.value);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayStr = nextDay.toISOString().split('T')[0];
            checkOutDate.min = nextDayStr;
            
            if (checkOutDate.value && checkOutDate.value < nextDayStr) {
                checkOutDate.value = nextDayStr;
            }
        }
        updateSummary();
    });
    checkOutDate.addEventListener('change', updateSummary);
    
    updateSummary();
});
</script>
<?= $this->endSection() ?>
