<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['hotel_id', 'room_number', 'room_type', 'price', 'status', 'description', 'capacity', 'facilities', 'created_at', 'updated_at'];
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

    public function getRoomsByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    public function updateRoomStatus($roomId, $status)
    {
        return $this->update($roomId, ['status' => $status]);
    }
    
    public function getRoomsByHotelId($hotelId)
    {
        return $this->where('hotel_id', $hotelId)->findAll();
    }

    public function getRoomPriceForDate($roomId, $date)
    {
        $db = \Config\Database::connect();
        $customPrice = $db->table('room_prices')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->get()
            ->getRowArray();

        if ($customPrice) {
            return $customPrice['price'];
        }

        $room = $this->find($roomId);
        return $room ? $room['price'] : 0;
    }

    public function setRoomPriceForDate($roomId, $date, $price)
    {
        $db = \Config\Database::connect();
        $existingPrice = $db->table('room_prices')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->get()
            ->getRowArray();

        if ($existingPrice) {
            return $db->table('room_prices')
                ->where('id', $existingPrice['id'])
                ->update(['price' => $price]);
        } else {
            return $db->table('room_prices')->insert([
                'room_id' => $roomId,
                'date' => $date,
                'price' => $price
            ]);
        }
    }

    public function getRoomPricesForPeriod($roomId, $startDate, $endDate)
    {
        $db = \Config\Database::connect();
        return $db->table('room_prices')
            ->where('room_id', $roomId)
            ->where('date >=', $startDate)
            ->where('date <=', $endDate)
            ->get()
            ->getResultArray();
    }
}
