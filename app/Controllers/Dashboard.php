<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\ReservationModel;
use App\Models\GuestModel;
use App\Models\HousekeepingModel;
use App\Models\ReportModel;

class Dashboard extends BaseController
{
    protected $roomModel;
    protected $reservationModel;
    protected $guestModel;
    protected $housekeepingModel;
    protected $reportModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->reservationModel = new ReservationModel();
        $this->guestModel = new GuestModel();
        $this->housekeepingModel = new HousekeepingModel();
        $this->reportModel = new ReportModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $today = date('Y-m-d');
        
        $totalRooms = count($this->roomModel->findAll());
        $availableRooms = count($this->roomModel->getRoomsByStatus('available'));
        $occupiedRooms = count($this->roomModel->getRoomsByStatus('occupied'));
        $dirtyRooms = count($this->roomModel->getRoomsByStatus('dirty'));
        
        $todayCheckIns = $this->reservationModel->select('reservations.*, rooms.room_number, guests.name as guest_name')
            ->join('rooms', 'rooms.id = reservations.room_id')
            ->join('guests', 'guests.id = reservations.guest_id')
            ->where('DATE(check_in_date)', $today)
            ->where('reservations.status', 'pending')
            ->findAll();
            
        $todayCheckOuts = $this->reservationModel->select('reservations.*, rooms.room_number, guests.name as guest_name')
            ->join('rooms', 'rooms.id = reservations.room_id')
            ->join('guests', 'guests.id = reservations.guest_id')
            ->where('DATE(check_out_date)', $today)
            ->where('reservations.status', 'checked_in')
            ->findAll();
        
        $housekeepingTasks = $this->housekeepingModel->select('housekeeping.*, rooms.room_number')
            ->join('rooms', 'rooms.id = housekeeping.room_id')
            ->where('housekeeping.scheduled_date', $today)
            ->findAll();
        
        $occupancyRate = $this->reportModel->getOccupancyRate($today);
        
        $data = [
            'title' => 'Dashboard',
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupiedRooms' => $occupiedRooms,
            'dirtyRooms' => $dirtyRooms,
            'todayCheckIns' => $todayCheckIns,
            'todayCheckOuts' => $todayCheckOuts,
            'housekeepingTasks' => $housekeepingTasks,
            'occupancyRate' => $occupancyRate
        ];
        
        return view('dashboard', $data);
    }
}
