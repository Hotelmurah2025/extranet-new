<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hotel Extranet' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css">
    <style>
        body {
            padding-top: 56px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .nav-link {
            color: #495057;
        }
        .nav-link:hover {
            background-color: #e9ecef;
        }
        .nav-link.active {
            color: #007bff;
            font-weight: bold;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/dashboard') ?>">Hotel Extranet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/dashboard') ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/reservation') ?>">Reservations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/room') ?>">Rooms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/guest') ?>">Guests</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/housekeeping') ?>">Housekeeping</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/report') ?>">Reports</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= session()->get('username') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= site_url('/auth/logout') ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('/auth/login') ?>">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (session()->get('isLoggedIn')): ?>
                <div class="col-md-2 sidebar p-0">
                    <div class="list-group list-group-flush">
                        <a href="<?= site_url('/dashboard') ?>" class="list-group-item list-group-item-action <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="<?= site_url('/reservation') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'reservation') === 0 ? 'active' : '' ?>">
                            <i class="bi bi-calendar-check me-2"></i> Reservations
                        </a>
                        <a href="<?= site_url('/room') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'room') === 0 ? 'active' : '' ?>">
                            <i class="bi bi-door-closed me-2"></i> Rooms
                        </a>
                        <a href="<?= site_url('/guest') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'guest') === 0 ? 'active' : '' ?>">
                            <i class="bi bi-people me-2"></i> Guests
                        </a>
                        <a href="<?= site_url('/housekeeping') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'housekeeping') === 0 ? 'active' : '' ?>">
                            <i class="bi bi-brush me-2"></i> Housekeeping
                        </a>
                        <a href="<?= site_url('/report') ?>" class="list-group-item list-group-item-action <?= strpos(uri_string(), 'report') === 0 ? 'active' : '' ?>">
                            <i class="bi bi-graph-up me-2"></i> Reports
                        </a>
                    </div>
                </div>
                <div class="col-md-10 content">
            <?php else: ?>
                <div class="col-md-12 content">
            <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?= $this->renderSection('content') ?>
