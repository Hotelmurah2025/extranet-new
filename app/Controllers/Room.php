<?php

namespace App\Controllers;

use App\Models\RoomModel;

class Room extends BaseController
{
    protected $roomModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Room Management',
            'rooms' => $this->roomModel->getAll()
        ];

        return view('room/list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Room'
        ];

        return view('room/create', $data);
    }

    public function store()
    {
        $rules = [
            'room_number' => 'required|is_unique[rooms.room_number]',
            'room_type' => 'required',
            'price' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return view('room/create', [
                'validation' => $this->validator,
                'title' => 'Add New Room'
            ]);
        }

        $data = [
            'room_number' => $this->request->getPost('room_number'),
            'room_type' => $this->request->getPost('room_type'),
            'price' => $this->request->getPost('price'),
            'status' => 'available',
            'description' => $this->request->getPost('description')
        ];

        $this->roomModel->save($data);
        session()->setFlashdata('success', 'Room added successfully');
        return redirect()->to('/room');
    }

    public function edit($id)
    {
        $room = $this->roomModel->getById($id);

        if (!$room) {
            session()->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $data = [
            'title' => 'Edit Room',
            'room' => $room
        ];

        return view('room/edit', $data);
    }

    public function update($id)
    {
        $room = $this->roomModel->getById($id);

        if (!$room) {
            session()->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $rules = [
            'room_number' => 'required|is_unique[rooms.room_number,id,' . $id . ']',
            'room_type' => 'required',
            'price' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return view('room/edit', [
                'validation' => $this->validator,
                'title' => 'Edit Room',
                'room' => $room
            ]);
        }

        $data = [
            'room_number' => $this->request->getPost('room_number'),
            'room_type' => $this->request->getPost('room_type'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description')
        ];

        $this->roomModel->update($id, $data);
        session()->setFlashdata('success', 'Room updated successfully');
        return redirect()->to('/room');
    }

    public function delete($id)
    {
        $room = $this->roomModel->getById($id);

        if (!$room) {
            session()->setFlashdata('error', 'Room not found');
            return redirect()->to('/room');
        }

        $this->roomModel->delete($id);
        session()->setFlashdata('success', 'Room deleted successfully');
        return redirect()->to('/room');
    }
}
