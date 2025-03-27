<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', ['title' => 'Login']);
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findUserByUsername($username);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);
            return redirect()->to('/dashboard');
        } else {
            session()->setFlashdata('error', 'Invalid username or password');
            return redirect()->back();
        }
    }

    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return view('auth/register', [
                'validation' => $this->validator
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'receptionist' // Default role
        ];

        $this->userModel->save($data);
        session()->setFlashdata('success', 'Registration successful. You can now login.');
        return redirect()->to('/auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
