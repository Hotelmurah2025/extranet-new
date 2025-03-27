<?php

namespace App\Controllers;

use App\Models\HousekeepingModel;
use App\Models\RoomModel;
use App\Models\UserModel;

class Housekeeping extends BaseController
{
    protected $housekeepingModel;
    protected $roomModel;
    protected $userModel;

    public function __construct()
    {
        $this->housekeepingModel = new HousekeepingModel();
        $this->roomModel = new RoomModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $data = [
            'title' => 'Housekeeping Tasks',
            'tasks' => $this->housekeepingModel->getSchedule($date),
            'date' => $date
        ];

        return view('housekeeping/list', $data);
    }

    public function schedule()
    {
        $data = [
            'title' => 'Housekeeping Schedule',
            'rooms' => $this->roomModel->findAll(),
            'staff' => $this->userModel->where('role', 'housekeeping')->findAll()
        ];

        return view('housekeeping/schedule', $data);
    }

    public function createTask()
    {
        $rules = [
            'room_id' => 'required',
            'scheduled_date' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'room_id' => $this->request->getPost('room_id'),
            'scheduled_date' => $this->request->getPost('scheduled_date'),
            'assigned_to' => $this->request->getPost('assigned_to'),
            'status' => 'pending',
            'notes' => $this->request->getPost('notes')
        ];

        $this->housekeepingModel->insert($data);
        session()->setFlashdata('success', 'Housekeeping task created successfully');
        return redirect()->to('/housekeeping');
    }

    public function updateStatus()
    {
        $taskId = $this->request->getPost('task_id');
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('notes');

        if ($this->housekeepingModel->updateStatus($taskId, $status, $notes)) {
            session()->setFlashdata('success', 'Task status updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update task status');
        }

        return redirect()->to('/housekeeping');
    }

    public function assign()
    {
        $taskId = $this->request->getPost('task_id');
        $staffId = $this->request->getPost('staff_id');

        $this->housekeepingModel->update($taskId, ['assigned_to' => $staffId]);
        session()->setFlashdata('success', 'Task assigned successfully');
        return redirect()->to('/housekeeping');
    }

    public function staffTasks()
    {
        $staffId = session()->get('id');
        $date = $this->request->getGet('date') ?? date('Y-m-d');

        $tasks = $this->housekeepingModel->select('housekeeping.*, rooms.room_number')
            ->join('rooms', 'rooms.id = housekeeping.room_id')
            ->where('assigned_to', $staffId)
            ->where('scheduled_date', $date)
            ->findAll();

        $data = [
            'title' => 'My Tasks',
            'tasks' => $tasks,
            'date' => $date
        ];

        return view('housekeeping/staff_tasks', $data);
    }
}
