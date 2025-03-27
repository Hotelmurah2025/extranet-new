<?php

namespace App\Controllers;

use App\Models\GuestModel;

class Guest extends BaseController
{
    protected $guestModel;

    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Guest Management',
            'guests' => $this->guestModel->getAll()
        ];

        return view('guest/list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New Guest'
        ];

        return view('guest/create', $data);
    }

    public function store()
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[guests.email]',
            'phone' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('guest/create', [
                'validation' => $this->validator,
                'title' => 'Add New Guest'
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'id_number' => $this->request->getPost('id_number')
        ];

        $this->guestModel->save($data);
        session()->setFlashdata('success', 'Guest added successfully');
        return redirect()->to('/guest');
    }

    public function edit($id)
    {
        $guest = $this->guestModel->getById($id);

        if (!$guest) {
            session()->setFlashdata('error', 'Guest not found');
            return redirect()->to('/guest');
        }

        $data = [
            'title' => 'Edit Guest',
            'guest' => $guest
        ];

        return view('guest/edit', $data);
    }

    public function update($id)
    {
        $guest = $this->guestModel->getById($id);

        if (!$guest) {
            session()->setFlashdata('error', 'Guest not found');
            return redirect()->to('/guest');
        }

        $rules = [
            'name' => 'required',
            'email' => 'required|valid_email|is_unique[guests.email,id,' . $id . ']',
            'phone' => 'required'
        ];

        if (!$this->validate($rules)) {
            return view('guest/edit', [
                'validation' => $this->validator,
                'title' => 'Edit Guest',
                'guest' => $guest
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'id_number' => $this->request->getPost('id_number')
        ];

        $this->guestModel->update($id, $data);
        session()->setFlashdata('success', 'Guest updated successfully');
        return redirect()->to('/guest');
    }

    public function view($id)
    {
        $guest = $this->guestModel->getById($id);

        if (!$guest) {
            session()->setFlashdata('error', 'Guest not found');
            return redirect()->to('/guest');
        }

        $bookingHistory = $this->guestModel->getGuestBookingHistory($id);

        $data = [
            'title' => 'Guest Details',
            'guest' => $guest,
            'bookingHistory' => $bookingHistory
        ];

        return view('guest/view', $data);
    }

    public function delete($id)
    {
        $guest = $this->guestModel->getById($id);

        if (!$guest) {
            session()->setFlashdata('error', 'Guest not found');
            return redirect()->to('/guest');
        }

        $this->guestModel->delete($id);
        session()->setFlashdata('success', 'Guest deleted successfully');
        return redirect()->to('/guest');
    }
}
