<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reservation Calendar</h1>
        <div>
            <a href="<?= site_url('/reservation') ?>" class="btn btn-secondary me-2">
                <i class="bi bi-list"></i> List View
            </a>
            <a href="<?= site_url('/reservation/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> New Reservation
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-9">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0">Reservation Calendar</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end">
                                <select id="statusFilter" class="form-select" style="width: auto;">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="checked_in">Checked In</option>
                                    <option value="checked_out">Checked Out</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Legend</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="legend-color" style="background-color: #ffc107;"></div>
                        <div class="ms-2">Pending</div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="legend-color" style="background-color: #28a745;"></div>
                        <div class="ms-2">Checked In</div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="legend-color" style="background-color: #6c757d;"></div>
                        <div class="ms-2">Checked Out</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="legend-color" style="background-color: #dc3545;"></div>
                        <div class="ms-2">Cancelled</div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <div>Today's Check-ins:</div>
                        <div id="todayCheckIns">-</div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <div>Today's Check-outs:</div>
                        <div id="todayCheckOuts">-</div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>Current Occupancy:</div>
                        <div id="currentOccupancy">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const statusFilter = document.getElementById('statusFilter');
    
    function fetchQuickStats() {
        document.getElementById('todayCheckIns').textContent = '3';
        document.getElementById('todayCheckOuts').textContent = '2';
        document.getElementById('currentOccupancy').textContent = '75%';
    }
    
    fetchQuickStats();
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '<?= site_url('/reservation/getCalendarData') ?>',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        },
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                return false;
            }
        },
        eventDidMount: function(info) {
            if (info.event.extendedProps.status) {
                let bgColor = '#3788d8'; // default blue
                let textColor = '#ffffff';
                
                switch(info.event.extendedProps.status) {
                    case 'pending':
                        bgColor = '#ffc107'; // warning yellow
                        textColor = '#212529';
                        break;
                    case 'checked_in':
                        bgColor = '#28a745'; // success green
                        break;
                    case 'checked_out':
                        bgColor = '#6c757d'; // secondary gray
                        break;
                    case 'cancelled':
                        bgColor = '#dc3545'; // danger red
                        break;
                }
                
                info.el.style.backgroundColor = bgColor;
                info.el.style.borderColor = bgColor;
                info.el.style.color = textColor;
                
                const currentFilter = statusFilter.value;
                if (currentFilter !== '' && info.event.extendedProps.status !== currentFilter) {
                    info.el.style.display = 'none';
                }
            }
            
            $(info.el).tooltip({
                title: `${info.event.title}<br>
                       Room: ${info.event.extendedProps.room || 'N/A'}<br>
                       Guest: ${info.event.extendedProps.guest || 'N/A'}<br>
                       Status: ${info.event.extendedProps.status || 'N/A'}`,
                placement: 'top',
                trigger: 'hover',
                html: true,
                container: 'body'
            });
        },
        dayMaxEvents: true, // allow "more" link when too many events
        eventDisplay: 'block', // make events more visible
        nowIndicator: true, // show current time indicator
        businessHours: {
            daysOfWeek: [0, 1, 2, 3, 4, 5, 6], // 0=Sunday, 1=Monday, etc.
            startTime: '08:00',
            endTime: '18:00',
        }
    });
    
    calendar.render();
    
    statusFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
<?= $this->endSection() ?>
