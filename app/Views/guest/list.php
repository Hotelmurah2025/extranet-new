<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Guest Management</h1>
        <a href="<?= site_url('/guest/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Guest
        </a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" id="guestSearch" class="form-control" placeholder="Search guests...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="guestsTable">
                    <thead>
                        <tr>
                            <th>Name <i class="bi bi-arrow-down-up sort-icon" data-sort="name"></i></th>
                            <th>Email <i class="bi bi-arrow-down-up sort-icon" data-sort="email"></i></th>
                            <th>Phone <i class="bi bi-arrow-down-up sort-icon" data-sort="phone"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($guests)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No guests found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($guests as $guest): ?>
                                <tr>
                                    <td><?= $guest['name'] ?></td>
                                    <td><?= $guest['email'] ?></td>
                                    <td><?= $guest['phone'] ?></td>
                                    <td>
                                        <a href="<?= site_url('/guest/view/' . $guest['id']) ?>" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="<?= site_url('/guest/edit/' . $guest['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $guest['id'] ?>">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?= $guest['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $guest['id'] ?>" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel<?= $guest['id'] ?>">Confirm Delete</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete guest <?= $guest['name'] ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <a href="<?= site_url('/guest/delete/' . $guest['id']) ?>" class="btn btn-danger">Delete</a>
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
    const guestsTable = document.getElementById('guestsTable');
    const guestSearch = document.getElementById('guestSearch');
    const searchButton = document.getElementById('searchButton');
    const sortIcons = document.querySelectorAll('.sort-icon');
    
    let currentSort = {
        column: null,
        direction: 'asc'
    };
    
    function filterTable() {
        const searchTerm = guestSearch.value.toLowerCase();
        const rows = guestsTable.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const phone = row.cells[2].textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || 
                                 name.includes(searchTerm) || 
                                 email.includes(searchTerm) ||
                                 phone.includes(searchTerm);
            
            row.style.display = matchesSearch ? '' : 'none';
        });
    }
    
    function sortTable(column) {
        const rows = Array.from(guestsTable.querySelectorAll('tbody tr'));
        const direction = (currentSort.column === column && currentSort.direction === 'asc') ? 'desc' : 'asc';
        
        rows.sort((a, b) => {
            let valueA, valueB;
            
            switch(column) {
                case 'name':
                    valueA = a.cells[0].textContent;
                    valueB = b.cells[0].textContent;
                    break;
                case 'email':
                    valueA = a.cells[1].textContent;
                    valueB = b.cells[1].textContent;
                    break;
                case 'phone':
                    valueA = a.cells[2].textContent;
                    valueB = b.cells[2].textContent;
                    break;
            }
            
            if (valueA < valueB) return direction === 'asc' ? -1 : 1;
            if (valueA > valueB) return direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        const tbody = guestsTable.querySelector('tbody');
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
    guestSearch.addEventListener('keyup', filterTable);
    
    sortIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            sortTable(this.dataset.sort);
        });
        
        icon.style.cursor = 'pointer';
    });
});
</script>
<?= $this->endSection() ?>
