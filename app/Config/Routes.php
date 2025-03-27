<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/store', 'Auth::store');
$routes->get('/auth/logout', 'Auth::logout');

$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->group('room', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Room::index');
    $routes->get('create', 'Room::create');
    $routes->post('store', 'Room::store');
    $routes->get('edit/(:num)', 'Room::edit/$1');
    $routes->post('update/(:num)', 'Room::update/$1');
    $routes->get('delete/(:num)', 'Room::delete/$1');
});

$routes->group('guest', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Guest::index');
    $routes->get('create', 'Guest::create');
    $routes->post('store', 'Guest::store');
    $routes->get('view/(:num)', 'Guest::view/$1');
    $routes->get('edit/(:num)', 'Guest::edit/$1');
    $routes->post('update/(:num)', 'Guest::update/$1');
    $routes->get('delete/(:num)', 'Guest::delete/$1');
});

$routes->group('reservation', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Reservation::index');
    $routes->get('create', 'Reservation::create');
    $routes->post('store', 'Reservation::store');
    $routes->get('view/(:num)', 'Reservation::view/$1');
    $routes->get('edit/(:num)', 'Reservation::edit/$1');
    $routes->post('update/(:num)', 'Reservation::update/$1');
    $routes->get('delete/(:num)', 'Reservation::delete/$1');
    $routes->get('calendar', 'Reservation::calendar');
    $routes->get('getCalendarData', 'Reservation::getCalendarData');
    $routes->get('check_availability', 'Reservation::checkAvailability');
    $routes->get('check_in/(:num)', 'Reservation::checkIn/$1');
    $routes->get('check_out/(:num)', 'Reservation::checkOut/$1');
    $routes->get('cancel/(:num)', 'Reservation::cancel/$1');
});

$routes->group('housekeeping', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Housekeeping::index');
    $routes->get('schedule', 'Housekeeping::schedule');
    $routes->post('createTask', 'Housekeeping::createTask');
    $routes->post('updateStatus', 'Housekeeping::updateStatus');
    $routes->post('assign', 'Housekeeping::assign');
    $routes->get('staffTasks', 'Housekeeping::staffTasks');
});

$routes->group('report', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Report::index');
    $routes->get('daily', 'Report::daily');
    $routes->get('monthly', 'Report::monthly');
    $routes->get('occupancy', 'Report::occupancy');
    $routes->get('revenue', 'Report::revenue');
});
