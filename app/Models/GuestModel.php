<?php

namespace App\Models;

use CodeIgniter\Model;

class GuestModel extends Model
{
    protected $table = 'guests';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'phone', 'address', 'id_number', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function getGuestBookingHistory($guestId)
    {
        $reservationModel = new ReservationModel();
        return $reservationModel->select('reservations.*, rooms.room_number')
            ->join('rooms', 'rooms.id = reservations.room_id')
            ->where('guest_id', $guestId)
            ->orderBy('check_in_date', 'DESC')
            ->findAll();
    }
}
