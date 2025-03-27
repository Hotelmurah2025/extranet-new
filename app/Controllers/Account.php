<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HotelModel;

class Account extends BaseController
{
    protected $userModel;
    protected $hotelModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->hotelModel = new HotelModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User tidak ditemukan');
        }
        
        $data = [
            'title' => 'Pengaturan Akun',
            'user' => $user
        ];
        
        return view('account/index', $data);
    }
    
    public function updateProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User tidak ditemukan');
        }
        
        $rules = [
            'username' => 'required|min_length[3]',
            'email' => 'required|valid_email'
        ];
        
        if ($this->request->getPost('username') !== $user['username']) {
            $rules['username'] .= '|is_unique[users.username]';
        }
        
        if ($this->request->getPost('email') !== $user['email']) {
            $rules['email'] .= '|is_unique[users.email]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email')
        ];
        
        $this->userModel->update($userId, $data);
        
        $sessionData = [
            'username' => $data['username'],
            'email' => $data['email']
        ];
        
        session()->set($sessionData);
        
        return redirect()->to('/account')->with('success', 'Profil berhasil diperbarui');
    }
    
    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'User tidak ditemukan');
        }
        
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        
        if (!$this->userModel->verifyPassword($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Password saat ini tidak valid');
        }
        
        $this->userModel->update($userId, ['password' => $newPassword]);
        
        return redirect()->to('/account')->with('success', 'Password berhasil diubah');
    }
    
    public function viewHotels()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }
        
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if ($role === 'admin_pusat') {
            $hotels = $this->hotelModel->findAll();
        } else {
            $hotels = $this->userModel->getUserHotels($userId);
        }
        
        $data = [
            'title' => 'Hotel Saya',
            'hotels' => $hotels,
            'role' => $role
        ];
        
        return view('account/hotels', $data);
    }
}
