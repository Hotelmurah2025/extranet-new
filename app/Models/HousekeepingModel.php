<?php

namespace App\Models;

use CodeIgniter\Model;

class HousekeepingModel extends Model
{
    protected $table = 'housekeeping';
    protected $primaryKey = 'id';
    protected $allowedFields = ['room_id', 'status', 'scheduled_date', 'completed_date', 'assigned_to', 'notes', 'created_at', 'updated_at'];
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

    public function getSchedule($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        return $this->select('housekeeping.*, rooms.room_number, users.username as assigned_to_name')
            ->join('rooms', 'rooms.id = housekeeping.room_id')
            ->join('users', 'users.id = housekeeping.assigned_to', 'left')
            ->where('scheduled_date', $date)
            ->findAll();
    }

    public function createTask($roomId, $scheduledDate = null)
    {
        if (!$scheduledDate) {
            $scheduledDate = date('Y-m-d');
        }
        
        $data = [
            'room_id' => $roomId,
            'status' => 'pending',
            'scheduled_date' => $scheduledDate
        ];
        
        return $this->insert($data);
    }

    public function updateStatus($taskId, $status, $notes = null)
    {
        $data = [
            'status' => $status
        ];
        
        if ($status === 'completed') {
            $data['completed_date'] = date('Y-m-d H:i:s');
            
            $task = $this->find($taskId);
            if ($task) {
                $roomModel = new RoomModel();
                $roomModel->updateRoomStatus($task['room_id'], 'available');
            }
        }
        
        if ($notes) {
            $data['notes'] = $notes;
        }
        
        return $this->update($taskId, $data);
    }
}
