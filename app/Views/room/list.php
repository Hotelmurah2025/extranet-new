<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Room Management</h1>
        <a href="<?= site_url('/room/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Room
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="roomSearch" class="form-control" placeholder="Search rooms...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end">
                        <select id="statusFilter" class="form-select me-2" style="width: auto;">
                            <option value="">All Statuses</option>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="dirty">Dirty</option>
                        </select>
                        <select id="typeFilter" class="form-select" style="width: auto;">
                            <option value="">All Types</option>
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Suite">Suite</option>
                            <option value="Executive">Executive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="roomsTable">
                    <thead>
                        <tr>
                            <th>Room Number <i class="bi bi-arrow-down-up sort-icon" data-sort="room_number"></i></th>
                            <th>Room Type <i class="bi bi-arrow-down-up sort-icon" data-sort="room_type"></i></th>
                            <th>Price <i class="bi bi-arrow-down-up sort-icon" data-sort="price"></i></th>
                            <th>Status <i class="bi bi-arrow-down-up sort-icon" data-sort="status"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rooms)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No rooms found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?= $room['room_number'] ?></td>
                                    <td><?= $room['room_type'] ?></td>
                                    <td><?= number_format($room['price'], 2) ?></td>
                                    <td>
                                        <?php if ($room['status'] === 'available'): ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php elseif ($room['status'] === 'occupied'): ?>
                                            <span class="badge bg-warning">Occupied</span>
                                        <?php elseif ($room['status'] === 'dirty'): ?>
                                            <span class="badge bg-danger">Dirty</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= ucfirst($room['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('/room/edit/' . $room['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $room['id'] ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $room['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $room['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $room['id'] ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete Room <?= $room['room_number'] ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="<?= site_url('/room/delete/' . $room['id']) ?>" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomsTable = document.getElementById('roomsTable');
    const roomSearch = document.getElementById('roomSearch');
    const searchButton = document.getElementById('searchButton');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const sortIcons = document.querySelectorAll('.sort-icon');
    
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    function filterTable() {
        const searchTerm = roomSearch.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();
        const typeValue = typeFilter.value;
        const rows = roomsTable.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const roomNumber = row.cells[0].textContent.toLowerCase();
            const roomType = row.cells[1].textContent;
            const statusCell = row.cells[3].querySelector('.badge');
            const status = statusCell ? statusCell.textContent.toLowerCase() : '';
            
            const matchesSearch = searchTerm === '' || 
                                 roomNumber.includes(searchTerm) || 
                                 roomType.toLowerCase().includes(searchTerm);
            const matchesStatus = statusValue === '' || status.includes(statusValue);
            const matchesType = typeValue === '' || roomType === typeValue;
            
            row.style.display = (matchesSearch && matchesStatus && matchesType) ? '' : 'none';
        });
    }
    
    function sortTable(column) {
        const rows = Array.from(roomsTable.querySelectorAll('tbody tr'));
        const direction = (currentSort.column === column && currentSort.direction === 'asc') ? 'desc' : 'asc';
        
        rows.sort((a, b) => {
            let valueA, valueB;
            
            switch(column) {
                case 'room_number':
                    valueA = a.cells[0].textContent;
                    valueB = b.cells[0].textContent;
                    if (!isNaN(valueA) && !isNaN(valueB)) {
                        valueA = parseInt(valueA);
                        valueB = parseInt(valueB);
                    }
                    break;
                case 'room_type':
                    valueA = a.cells[1].textContent;
                    valueB = b.cells[1].textContent;
                    break;
                case 'price':
                    valueA = parseFloat(a.cells[2].textContent.replace(/[^0-9.-]+/g, ''));
                    valueB = parseFloat(b.cells[2].textContent.replace(/[^0-9.-]+/g, ''));
                    break;
                case 'status':
                    const statusA = a.cells[3].querySelector('.badge');
                    const statusB = b.cells[3].querySelector('.badge');
                    valueA = statusA ? statusA.textContent : '';
                    valueB = statusB ? statusB.textContent : '';
                    break;
            }
            
            if (valueA < valueB) return direction === 'asc' ? -1 : 1;
            if (valueA > valueB) return direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        const tbody = roomsTable.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row));
        
        currentSort.column = column;
        currentSort.direction = direction;
        
        sortIcons.forEach(icon => {
            if (icon.dataset.sort === column) {
                icon.className = `bi ${direction === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down'} sort-icon`;
            } else {
                icon.className = 'bi bi-arrow-down-up sort-icon';
            }
        });
    }
    
    searchButton.addEventListener('click', filterTable);
    roomSearch.addEventListener('keyup', filterTable);
    statusFilter.addEventListener('change', filterTable);
    typeFilter.addEventListener('change', filterTable);
    
    sortIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            sortTable(this.dataset.sort);
        });
        
        icon.style.cursor = 'pointer';
    });
});
</script>
<?= $this->endSection() ?>
