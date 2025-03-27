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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Hotel: <?= $hotel['name'] ?></h5>
                            <p>Nomor Kamar: <?= $room['room_number'] ?></p>
                            <p>Tipe Kamar: <?= $room['room_type'] ?></p>
                            <p>Harga Default: Rp <?= number_format($room['price'], 0, ',', '.') ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="<?= base_url('room/hotelRooms/' . $hotel['id']) ?>" class="btn btn-secondary">Kembali ke Daftar Kamar</a>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Atur Harga per Tanggal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="single-date">Tanggal</label>
                                                <input type="date" id="single-date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="single-price">Harga</label>
                                                <input type="number" id="single-price" class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button id="set-price-btn" class="btn btn-primary">Atur Harga</button>
                                        <button id="reset-price-btn" class="btn btn-warning">Reset ke Harga Default</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Atur Harga untuk Rentang Tanggal</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="range-start-date">Tanggal Mulai</label>
                                                <input type="date" id="range-start-date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="range-end-date">Tanggal Selesai</label>
                                                <input type="date" id="range-end-date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="range-price">Harga</label>
                                                <input type="number" id="range-price" class="form-control" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button id="set-range-price-btn" class="btn btn-primary">Atur Harga untuk Rentang</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Kalender Harga</h5>
                                </div>
                                <div class="card-body">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomId = <?= $room['id'] ?>;
    const defaultPrice = <?= $room['price'] ?>;
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        events: function(info, successCallback, failureCallback) {
            fetch(`<?= base_url('room/getCalendarData') ?>?room_id=${roomId}&start_date=${info.startStr}&end_date=${info.endStr}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const events = data.data.map(item => {
                            return {
                                title: `Rp ${new Intl.NumberFormat('id-ID').format(item.price)}`,
                                start: item.date,
                                backgroundColor: item.isCustom ? '#28a745' : '#007bff',
                                borderColor: item.isCustom ? '#28a745' : '#007bff',
                                textColor: '#ffffff',
                                extendedProps: {
                                    price: item.price,
                                    isCustom: item.isCustom
                                }
                            };
                        });
                        successCallback(events);
                    } else {
                        alert(data.message);
                        failureCallback(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching calendar data:', error);
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            const date = info.event.start;
            const formattedDate = date.toISOString().split('T')[0];
            const price = info.event.extendedProps.price;
            
            document.getElementById('single-date').value = formattedDate;
            document.getElementById('single-price').value = price;
        }
    });
    
    calendar.render();
    
    document.getElementById('set-price-btn').addEventListener('click', function() {
        const date = document.getElementById('single-date').value;
        const price = document.getElementById('single-price').value;
        
        if (!date || !price) {
            alert('Silakan isi tanggal dan harga');
            return;
        }
        
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('date', date);
        formData.append('price', price);
        
        fetch('<?= base_url('room/setPriceForDate') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                calendar.refetchEvents();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error setting price:', error);
            alert('Terjadi kesalahan saat mengatur harga');
        });
    });
    
    document.getElementById('reset-price-btn').addEventListener('click', function() {
        const date = document.getElementById('single-date').value;
        
        if (!date) {
            alert('Silakan pilih tanggal');
            return;
        }
        
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('date', date);
        
        fetch('<?= base_url('room/resetPriceForDate') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById('single-price').value = defaultPrice;
                calendar.refetchEvents();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error resetting price:', error);
            alert('Terjadi kesalahan saat mereset harga');
        });
    });
    
    document.getElementById('set-range-price-btn').addEventListener('click', function() {
        const startDate = document.getElementById('range-start-date').value;
        const endDate = document.getElementById('range-end-date').value;
        const price = document.getElementById('range-price').value;
        
        if (!startDate || !endDate || !price) {
            alert('Silakan isi tanggal mulai, tanggal selesai, dan harga');
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            alert('Tanggal mulai harus lebih awal dari tanggal selesai');
            return;
        }
        
        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('start_date', startDate);
        formData.append('end_date', endDate);
        formData.append('price', price);
        
        fetch('<?= base_url('room/setPriceForRange') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                calendar.refetchEvents();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error setting price range:', error);
            alert('Terjadi kesalahan saat mengatur harga untuk rentang tanggal');
        });
    });
});
</script>
<?= $this->endSection() ?>
