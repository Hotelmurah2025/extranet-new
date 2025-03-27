<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['room_number', 'room_type', 'price', 'status', 'description', 'created_at', 'updated_at'];
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
}
