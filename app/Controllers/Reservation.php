<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\RoomModel;
use App\Models\GuestModel;

class Reservation extends BaseController
{
    protected $reservationModel;
    protected $roomModel;
    protected $guestModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
        $this->roomModel = new RoomModel();
        $this->guestModel = new GuestModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Reservation Management',
            'reservations' => $this->reservationModel->select('reservations.*, rooms.room_number, guests.name as guest_name')
                ->join('rooms', 'rooms.id = reservations.room_id')
                ->join('guests', 'guests.id = reservations.guest_id')
                ->findAll()
        ];

        return view('reservation/list', $data);
    }

    public function calendar()
    {
        $data = [
            'title' => 'Reservation Calendar'
        ];

        return view('reservation/calendar', $data);
    }

    public function getCalendarData()
    {
        $startDate = $this->request->getGet('start');
        $endDate = $this->request->getGet('end');

        $reservations = $this->reservationModel->getCalendarReservations($startDate, $endDate);

        $events = [];
        foreach ($reservations as $reservation) {
            $color = '#3788d8'; // Default color

            if ($reservation['status'] === 'checked_in') {
                $color = '#28a745'; // Green for checked-in
            } elseif ($reservation['status'] === 'checked_out') {
                $color = '#6c757d'; // Gray for checked-out
            } elseif ($reservation['status'] === 'pending') {
                $color = '#ffc107'; // Yellow for pending
            }

            $events[] = [
                'id' => $reservation['id'],
                'title' => $reservation['room_number'] . ' - ' . $reservation['guest_name'],
                'start' => $reservation['check_in_date'],
                'end' => $reservation['check_out_date'],
                'color' => $color,
                'url' => site_url('reservation/view/' . $reservation['id'])
            ];
        }

        return $this->response->setJSON($events);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Reservation',
            'rooms' => $this->roomModel->findAll(),
            'guests' => $this->guestModel->findAll()
        ];

        return view('reservation/create', $data);
    }

    public function store()
    {
        $rules = [
            'guest_id' => 'required',
            'room_id' => 'required',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return view('reservation/create', [
                'validation' => $this->validator,
                'title' => 'Create Reservation',
                'rooms' => $this->roomModel->findAll(),
                'guests' => $this->guestModel->findAll()
            ]);
        }

        $roomId = $this->request->getPost('room_id');
        $checkInDate = $this->request->getPost('check_in_date');
        $checkOutDate = $this->request->getPost('check_out_date');

        if (!$this->reservationModel->isRoomAvailable($roomId, $checkInDate, $checkOutDate)) {
            session()->setFlashdata('error', 'Room is not available for the selected dates');
            return redirect()->back()->withInput();
        }

        $room = $this->roomModel->find($roomId);
        $checkIn = new \DateTime($checkInDate);
        $checkOut = new \DateTime($checkOutDate);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPrice = $room['price'] * $nights;

        $data = [
            'guest_id' => $this->request->getPost('guest_id'),
            'room_id' => $roomId,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'status' => 'pending',
            'total_price' => $totalPrice,
            'notes' => $this->request->getPost('notes')
        ];

        $this->reservationModel->save($data);
        session()->setFlashdata('success', 'Reservation created successfully');
        return redirect()->to('/reservation');
    }

    public function edit($id)
    {
        $reservation = $this->reservationModel->find($id);

        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/reservation');
        }

        $data = [
            'title' => 'Edit Reservation',
            'reservation' => $reservation,
            'rooms' => $this->roomModel->findAll(),
            'guests' => $this->guestModel->findAll()
        ];

        return view('reservation/edit', $data);
    }

    public function update($id)
    {
        $reservation = $this->reservationModel->find($id);

        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/reservation');
        }

        $rules = [
            'guest_id' => 'required',
            'room_id' => 'required',
            'check_in_date' => 'required|valid_date',
            'check_out_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return view('reservation/edit', [
                'validation' => $this->validator,
                'title' => 'Edit Reservation',
                'reservation' => $reservation,
                'rooms' => $this->roomModel->findAll(),
                'guests' => $this->guestModel->findAll()
            ]);
        }

        $roomId = $this->request->getPost('room_id');
        $checkInDate = $this->request->getPost('check_in_date');
        $checkOutDate = $this->request->getPost('check_out_date');

        if (!$this->reservationModel->isRoomAvailable($roomId, $checkInDate, $checkOutDate, $id)) {
            session()->setFlashdata('error', 'Room is not available for the selected dates');
            return redirect()->back()->withInput();
        }

        $room = $this->roomModel->find($roomId);
        $checkIn = new \DateTime($checkInDate);
        $checkOut = new \DateTime($checkOutDate);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPrice = $room['price'] * $nights;

        $data = [
            'guest_id' => $this->request->getPost('guest_id'),
            'room_id' => $roomId,
            'check_in_date' => $checkInDate,
            'check_out_date' => $checkOutDate,
            'status' => $this->request->getPost('status'),
            'total_price' => $totalPrice,
            'notes' => $this->request->getPost('notes')
        ];

        $this->reservationModel->update($id, $data);
        session()->setFlashdata('success', 'Reservation updated successfully');
        return redirect()->to('/reservation');
    }

    public function view($id)
    {
        $reservation = $this->reservationModel->select('reservations.*, rooms.room_number, rooms.room_type, guests.name as guest_name, guests.email, guests.phone')
            ->join('rooms', 'rooms.id = reservations.room_id')
            ->join('guests', 'guests.id = reservations.guest_id')
            ->find($id);

        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/reservation');
        }

        $data = [
            'title' => 'Reservation Details',
            'reservation' => $reservation
        ];

        return view('reservation/view', $data);
    }

    public function check_in($id)
    {
        if ($this->reservationModel->checkIn($id)) {
            session()->setFlashdata('success', 'Guest checked in successfully');
        } else {
            session()->setFlashdata('error', 'Failed to check in guest');
        }

        return redirect()->to('/reservation');
    }

    public function check_out($id)
    {
        if ($this->reservationModel->checkOut($id)) {
            session()->setFlashdata('success', 'Guest checked out successfully');
        } else {
            session()->setFlashdata('error', 'Failed to check out guest');
        }

        return redirect()->to('/reservation');
    }

    public function cancel($id)
    {
        $reservation = $this->reservationModel->find($id);

        if (!$reservation) {
            session()->setFlashdata('error', 'Reservation not found');
            return redirect()->to('/reservation');
        }

        $this->reservationModel->update($id, ['status' => 'cancelled']);
        session()->setFlashdata('success', 'Reservation cancelled successfully');
        return redirect()->to('/reservation');
    }

    public function check_availability()
    {
        $roomId = $this->request->getGet('room_id');
        $checkInDate = $this->request->getGet('check_in_date');
        $checkOutDate = $this->request->getGet('check_out_date');
        $reservationId = $this->request->getGet('reservation_id');

        $isAvailable = $this->reservationModel->isRoomAvailable($roomId, $checkInDate, $checkOutDate, $reservationId);

        return $this->response->setJSON(['available' => $isAvailable]);
    }
}
