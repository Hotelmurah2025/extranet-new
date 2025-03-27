<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['guest_id', 'room_id', 'check_in_date', 'check_out_date', 'status', 'total_price', 'notes', 'created_at', 'updated_at'];
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

    public function getReservationsByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    public function isRoomAvailable($roomId, $checkInDate, $checkOutDate, $excludeReservationId = null)
    {
        $builder = $this->db->table('reservations')
            ->where('room_id', $roomId)
            ->where('status !=', 'cancelled')
            ->groupStart()
                ->where('check_in_date <=', $checkOutDate)
                ->where('check_out_date >=', $checkInDate)
            ->groupEnd();
        
        if ($excludeReservationId) {
            $builder->where('id !=', $excludeReservationId);
        }
        
        $count = $builder->countAllResults();
        
        return $count === 0;
    }

    public function checkIn($reservationId)
    {
        $reservation = $this->find($reservationId);
        if ($reservation) {
            $this->update($reservationId, ['status' => 'checked_in']);
            
            $roomModel = new RoomModel();
            $roomModel->updateRoomStatus($reservation['room_id'], 'occupied');
            
            return true;
        }
        return false;
    }

    public function checkOut($reservationId)
    {
        $reservation = $this->find($reservationId);
        if ($reservation) {
            $this->update($reservationId, ['status' => 'checked_out']);
            
            $roomModel = new RoomModel();
            $roomModel->updateRoomStatus($reservation['room_id'], 'dirty');
            
            $housekeepingModel = new HousekeepingModel();
            $housekeepingModel->createTask($reservation['room_id']);
            
            return true;
        }
        return false;
    }

    public function getCalendarReservations($startDate, $endDate)
    {
        return $this->select('reservations.*, rooms.room_number, guests.name as guest_name')
            ->join('rooms', 'rooms.id = reservations.room_id')
            ->join('guests', 'guests.id = reservations.guest_id')
            ->where('check_in_date <=', $endDate)
            ->where('check_out_date >=', $startDate)
            ->where('status !=', 'cancelled')
            ->findAll();
    }
}
