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

$routes->group('hotel', ['filter' => 'auth:admin_pusat,user_hotel'], function($routes) {
    $routes->get('/', 'Hotel::index');
    $routes->get('create', 'Hotel::create', ['filter' => 'auth:admin_pusat']);
    $routes->post('store', 'Hotel::store', ['filter' => 'auth:admin_pusat']);
    $routes->get('view/(:num)', 'Hotel::view/$1');
    $routes->get('edit/(:num)', 'Hotel::edit/$1', ['filter' => 'auth:admin_pusat']);
    $routes->post('update/(:num)', 'Hotel::update/$1', ['filter' => 'auth:admin_pusat']);
    $routes->get('delete/(:num)', 'Hotel::delete/$1', ['filter' => 'auth:admin_pusat']);
    $routes->get('users/(:num)', 'Hotel::users/$1', ['filter' => 'auth:admin_pusat']);
    $routes->post('addUser', 'Hotel::addUser', ['filter' => 'auth:admin_pusat']);
    $routes->post('removeUser', 'Hotel::removeUser', ['filter' => 'auth:admin_pusat']);
    $routes->get('deleteImage/(:num)/(:num)', 'Hotel::deleteImage/$1/$2', ['filter' => 'auth:admin_pusat']);
    $routes->post('setPrimaryImage', 'Hotel::setPrimaryImage', ['filter' => 'auth:admin_pusat']);
});

$routes->group('room', ['filter' => 'auth:admin_pusat,user_hotel'], function($routes) {
    $routes->get('/', 'Room::index');
    $routes->get('hotelRooms/(:num)', 'Room::hotelRooms/$1');
    $routes->get('create/(:num)', 'Room::create/$1');
    $routes->post('store', 'Room::store');
    $routes->get('view/(:num)', 'Room::view/$1');
    $routes->get('edit/(:num)', 'Room::edit/$1');
    $routes->post('update/(:num)', 'Room::update/$1');
    $routes->get('delete/(:num)', 'Room::delete/$1');
    $routes->get('calendar/(:num)', 'Room::calendar/$1');
    $routes->post('setPriceForDate', 'Room::setPriceForDate');
    $routes->post('setAvailability', 'Room::setAvailability');
    $routes->get('getCalendarData/(:num)', 'Room::getCalendarData/$1');
    $routes->get('getHotelRooms/(:num)', 'Room::getHotelRooms/$1');
    $routes->get('deleteImage/(:num)/(:num)', 'Room::deleteImage/$1/$2');
    $routes->post('setPrimaryImage', 'Room::setPrimaryImage');
});

$routes->group('reservation', ['filter' => 'auth:admin_pusat,user_hotel'], function($routes) {
    $routes->get('/', 'Reservation::index');
    $routes->get('filter', 'Reservation::filter');
    $routes->get('create', 'Reservation::create');
    $routes->post('store', 'Reservation::store');
    $routes->get('detail/(:num)', 'Reservation::detail/$1');
    $routes->get('edit/(:num)', 'Reservation::edit/$1');
    $routes->post('update/(:num)', 'Reservation::update/$1');
    $routes->get('delete/(:num)', 'Reservation::delete/$1');
    $routes->get('confirm/(:num)', 'Reservation::confirm/$1');
    $routes->get('cancel/(:num)', 'Reservation::cancel/$1');
    $routes->get('markAsPaid/(:num)', 'Reservation::markAsPaid/$1');
    $routes->get('export', 'Reservation::export');
    $routes->get('check_availability', 'Reservation::checkAvailability');
    $routes->get('check_in/(:num)', 'Reservation::checkIn/$1');
    $routes->get('check_out/(:num)', 'Reservation::checkOut/$1');
});

$routes->group('review', ['filter' => 'auth:admin_pusat,user_hotel'], function($routes) {
    $routes->get('/', 'Review::index');
    $routes->get('hotelReviews/(:num)', 'Review::hotelReviews/$1');
    $routes->get('detail/(:num)', 'Review::detail/$1');
});

$routes->group('account', ['filter' => 'auth:admin_pusat,user_hotel'], function($routes) {
    $routes->get('/', 'Account::index');
    $routes->post('updateProfile', 'Account::updateProfile');
    $routes->post('changePassword', 'Account::changePassword');
    $routes->get('hotels', 'Account::viewHotels');
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
