<?php

namespace App\Controllers;

use App\Models\HotelModel;
use App\Models\HotelImageModel;
use App\Models\UserModel;
use App\Models\ReviewModel;

class Hotel extends BaseController
{
    protected $hotelModel;
    protected $hotelImageModel;
    protected $userModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->hotelModel = new HotelModel();
        $this->hotelImageModel = new HotelImageModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = [
            'title' => 'Daftar Hotel',
            'hotels' => $this->hotelModel->findAll()
        ];

        return view('hotel/list', $data);
    }

    public function view($id)
    {
        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
        }

        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;

            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $id) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel ini');
            }
        }

        $db = \Config\Database::connect();
        $roomCount = $db->table('rooms')
            ->where('hotel_id', $id)
            ->countAllResults();
            
        $totalRooms = $roomCount > 0 ? $roomCount : 1; // Avoid division by zero
        $occupiedRooms = $db->table('rooms')
            ->where('hotel_id', $id)
            ->where('status', 'occupied')
            ->countAllResults();
        $occupancyRate = round(($occupiedRooms / $totalRooms) * 100);
        
        $data = [
            'title' => $hotel['name'],
            'hotel' => $hotel,
            'images' => $this->hotelImageModel->getByHotelId($id),
            'reviews' => $this->reviewModel->getByHotelId($id),
            'avgRating' => $this->reviewModel->getAverageRating($id),
            'roomCount' => $roomCount,
            'occupancyRate' => $occupancyRate,
            'averageRating' => $this->reviewModel->getAverageRating($id)
        ];

        return view('hotel/view', $data);
    }

    public function create()
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $data = [
            'title' => 'Tambah Hotel Baru'
        ];

        return view('hotel/create', $data);
    }

    public function store()
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'address' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'location' => $this->request->getPost('location'),
            'description' => $this->request->getPost('description'),
            'facilities' => $this->request->getPost('facilities')
        ];

        $this->hotelModel->save($data);
        $hotelId = $this->hotelModel->getInsertID();

        $images = $this->request->getFiles();
        if ($images && $images['images']) {
            foreach ($images['images'] as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $newName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/uploads/hotels', $newName);

                    $this->hotelImageModel->save([
                        'hotel_id' => $hotelId,
                        'image_path' => 'uploads/hotels/' . $newName,
                        'is_primary' => 0
                    ]);
                }
            }
        }

        return redirect()->to('/hotel')->with('success', 'Hotel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
        }

        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;

            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $id) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel ini');
            }
        }

        $data = [
            'title' => 'Edit Hotel',
            'hotel' => $hotel,
            'images' => $this->hotelImageModel->getByHotelId($id)
        ];

        return view('hotel/edit', $data);
    }

    public function update($id)
    {
        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
        }

        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;

            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $id) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel ini');
            }
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'address' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'address' => $this->request->getPost('address'),
            'location' => $this->request->getPost('location'),
            'description' => $this->request->getPost('description'),
            'facilities' => $this->request->getPost('facilities')
        ];

        $this->hotelModel->save($data);

        $images = $this->request->getFiles();
        if ($images && $images['images']) {
            foreach ($images['images'] as $image) {
                if ($image->isValid() && !$image->hasMoved()) {
                    $newName = $image->getRandomName();
                    $image->move(ROOTPATH . 'public/uploads/hotels', $newName);

                    $this->hotelImageModel->save([
                        'hotel_id' => $id,
                        'image_path' => 'uploads/hotels/' . $newName,
                        'is_primary' => 0
                    ]);
                }
            }
        }

        return redirect()->to('/hotel/view/' . $id)->with('success', 'Hotel berhasil diperbarui');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $hotel = $this->hotelModel->find($id);

        if (!$hotel) {
            return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
        }

        $this->hotelModel->delete($id);
        return redirect()->to('/hotel')->with('success', 'Hotel berhasil dihapus');
    }

    public function users($hotelId)
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $hotel = $this->hotelModel->find($hotelId);

        if (!$hotel) {
            return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $hotelUsers = $db->table('user_hotels')
            ->select('users.*')
            ->join('users', 'users.id = user_hotels.user_id')
            ->where('user_hotels.hotel_id', $hotelId)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Pengguna Hotel: ' . $hotel['name'],
            'hotel' => $hotel,
            'users' => $hotelUsers,
            'allUsers' => $this->userModel->where('role', 'user_hotel')->findAll()
        ];

        return view('hotel/users', $data);
    }

    public function addUser()
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $hotelId = $this->request->getPost('hotel_id');
        $userId = $this->request->getPost('user_id');

        $hotel = $this->hotelModel->find($hotelId);
        $user = $this->userModel->find($userId);

        if (!$hotel || !$user) {
            return redirect()->to('/hotel/users/' . $hotelId)->with('error', 'Hotel atau pengguna tidak ditemukan');
        }

        $this->userModel->addHotelToUser($userId, $hotelId);
        return redirect()->to('/hotel/users/' . $hotelId)->with('success', 'Pengguna berhasil ditambahkan ke hotel');
    }

    public function removeUser()
    {
        if (session()->get('role') !== 'admin_pusat') {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        $hotelId = $this->request->getPost('hotel_id');
        $userId = $this->request->getPost('user_id');

        $hotel = $this->hotelModel->find($hotelId);
        $user = $this->userModel->find($userId);

        if (!$hotel || !$user) {
            return redirect()->to('/hotel/users/' . $hotelId)->with('error', 'Hotel atau pengguna tidak ditemukan');
        }

        $this->userModel->removeHotelFromUser($userId, $hotelId);
        return redirect()->to('/hotel/users/' . $hotelId)->with('success', 'Pengguna berhasil dihapus dari hotel');
    }

    public function setPrimaryImage()
    {
        $imageId = $this->request->getPost('image_id');
        $hotelId = $this->request->getPost('hotel_id');
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;

            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $hotelId) {
                    $hasAccess = true;
                    break;
                }
            }

            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel ini');
            }
        }
        
        $this->hotelImageModel->setPrimaryImage($imageId, $hotelId);
        return redirect()->to('/hotel/edit/' . $hotelId)->with('success', 'Gambar utama berhasil diperbarui');
    }
}
