<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\HotelModel;
use App\Models\UserModel;

class Room extends BaseController
{
    protected $roomModel;
    protected $hotelModel;
    protected $userModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->hotelModel = new HotelModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if ($role === 'admin_pusat') {
            $rooms = $this->roomModel->getAll();
            $hotels = $this->hotelModel->getAll();
        } else {
            $userHotels = $this->userModel->getUserHotels($userId);
            $hotelIds = array_column($userHotels, 'id');
            
            if (empty($hotelIds)) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel manapun');
            }
            
            $rooms = [];
            $hotels = [];
            
            foreach ($hotelIds as $hotelId) {
                $hotelRooms = $this->roomModel->getRoomsByHotelId($hotelId);
                $rooms = array_merge($rooms, $hotelRooms);
                $hotels[] = $this->hotelModel->find($hotelId);
            }
        }
        
        $data = [
            'title' => 'Daftar Kamar',
            'rooms' => $rooms,
            'hotels' => $hotels
        ];
        
        return view('room/list', $data);
    }
    
    public function hotelRooms($hotelId)
    {
        $hotel = $this->hotelModel->find($hotelId);
        
        if (!$hotel) {
            return redirect()->to('/room')->with('error', 'Hotel tidak ditemukan');
        }
        
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
        
        $rooms = $this->roomModel->getRoomsByHotelId($hotelId);
        
        $data = [
            'title' => 'Kamar Hotel: ' . $hotel['name'],
            'rooms' => $rooms,
            'hotel' => $hotel
        ];
        
        return view('room/list', $data);
    }

    public function create($hotelId = null)
    {
        if (session()->get('role') === 'admin_pusat') {
            if ($hotelId) {
                $hotel = $this->hotelModel->find($hotelId);
                
                if (!$hotel) {
                    return redirect()->to('/hotel')->with('error', 'Hotel tidak ditemukan');
                }
                
                $hotels = [$hotel];
            } else {
                $hotels = $this->hotelModel->getAll();
            }
        } else {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            
            if (empty($userHotels)) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel manapun');
            }
            
            if ($hotelId) {
                $hasAccess = false;
                $hotel = null;
                
                foreach ($userHotels as $userHotel) {
                    if ($userHotel['id'] == $hotelId) {
                        $hasAccess = true;
                        $hotel = $userHotel;
                        break;
                    }
                }
                
                if (!$hasAccess) {
                    return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke hotel ini');
                }
                
                $hotels = [$hotel];
            } else {
                $hotels = $userHotels;
            }
        }
        
        $data = [
            'title' => 'Tambah Kamar Baru',
            'hotels' => $hotels,
            'selectedHotelId' => $hotelId
        ];
        
        return view('room/create', $data);
    }
    
    public function store()
    {
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
        
        $rules = [
            'hotel_id' => 'required',
            'room_number' => 'required',
            'room_type' => 'required',
            'price' => 'required|numeric',
            'capacity' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'hotel_id' => $hotelId,
            'room_number' => $this->request->getPost('room_number'),
            'room_type' => $this->request->getPost('room_type'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status') ?? 'available',
            'description' => $this->request->getPost('description'),
            'capacity' => $this->request->getPost('capacity'),
            'facilities' => $this->request->getPost('facilities')
        ];
        
        $this->roomModel->save($data);
        return redirect()->to('/room/hotelRooms/' . $hotelId)->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit($id)
    {
        $room = $this->roomModel->find($id);
        
        if (!$room) {
            return redirect()->to('/room')->with('error', 'Kamar tidak ditemukan');
        }
        
        $hotel = $this->hotelModel->find($room['hotel_id']);
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke kamar ini');
            }
        }
        
        $data = [
            'title' => 'Edit Kamar: ' . $room['room_number'],
            'room' => $room,
            'hotel' => $hotel
        ];
        
        return view('room/edit', $data);
    }
    
    public function update($id)
    {
        $room = $this->roomModel->find($id);
        
        if (!$room) {
            return redirect()->to('/room')->with('error', 'Kamar tidak ditemukan');
        }
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke kamar ini');
            }
        }
        
        $rules = [
            'room_number' => 'required',
            'room_type' => 'required',
            'price' => 'required|numeric',
            'capacity' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'id' => $id,
            'room_number' => $this->request->getPost('room_number'),
            'room_type' => $this->request->getPost('room_type'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status'),
            'description' => $this->request->getPost('description'),
            'capacity' => $this->request->getPost('capacity'),
            'facilities' => $this->request->getPost('facilities')
        ];
        
        $this->roomModel->save($data);
        return redirect()->to('/room/view/' . $id)->with('success', 'Kamar berhasil diperbarui');
    }
    
    public function delete($id)
    {
        $room = $this->roomModel->find($id);
        
        if (!$room) {
            return redirect()->to('/room')->with('error', 'Kamar tidak ditemukan');
        }
        
        $hotelId = $room['hotel_id'];
        
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
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke kamar ini');
            }
        }
        
        $this->roomModel->delete($id);
        return redirect()->to('/room/hotelRooms/' . $hotelId)->with('success', 'Kamar berhasil dihapus');
    }
    
    public function calendar($roomId = null)
    {
        $room = $this->roomModel->find($roomId);
        
        if (!$room) {
            return redirect()->to('/room')->with('error', 'Kamar tidak ditemukan');
        }
        
        $hotel = $this->hotelModel->find($room['hotel_id']);
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke kamar ini');
            }
        }
        
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        $customPrices = $this->roomModel->getRoomPricesForPeriod($roomId, $startDate, $endDate);
        
        $calendarPrices = [];
        foreach ($customPrices as $price) {
            $calendarPrices[$price['date']] = $price['price'];
        }
        
        $data = [
            'title' => 'Kalender Ketersediaan & Harga: ' . $room['room_number'],
            'room' => $room,
            'hotel' => $hotel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'calendarPrices' => $calendarPrices,
            'defaultPrice' => $room['price']
        ];
        
        return view('room/calendar', $data);
    }

    public function getCalendarData()
    {
        $roomId = $this->request->getGet('room_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        
        $room = $this->roomModel->find($roomId);
        
        if (!$room) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kamar tidak ditemukan']);
        }
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses ke kamar ini']);
            }
        }
        
        $customPrices = $this->roomModel->getRoomPricesForPeriod($roomId, $startDate, $endDate);
        
        $calendarData = [];
        $currentDate = new \DateTime($startDate);
        $lastDate = new \DateTime($endDate);
        
        while ($currentDate <= $lastDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $price = $room['price']; // Default price
            
            foreach ($customPrices as $customPrice) {
                if ($customPrice['date'] == $dateStr) {
                    $price = $customPrice['price'];
                    break;
                }
            }
            
            $calendarData[] = [
                'date' => $dateStr,
                'price' => $price,
                'isCustom' => $price != $room['price']
            ];
            
            $currentDate->modify('+1 day');
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $calendarData,
            'defaultPrice' => $room['price']
        ]);
    }
    
    public function setPriceForDate()
    {
        $roomId = $this->request->getPost('room_id');
        $date = $this->request->getPost('date');
        $price = $this->request->getPost('price');
        
        $room = $this->roomModel->find($roomId);
        
        if (!$room) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kamar tidak ditemukan']);
        }
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses ke kamar ini']);
            }
        }
        
        $this->roomModel->setRoomPriceForDate($roomId, $date, $price);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Harga berhasil diperbarui']);
    }
    
    public function setPriceForRange()
    {
        $roomId = $this->request->getPost('room_id');
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $price = $this->request->getPost('price');
        
        $room = $this->roomModel->find($roomId);
        
        if (!$room) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kamar tidak ditemukan']);
        }
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses ke kamar ini']);
            }
        }
        
        $currentDate = new \DateTime($startDate);
        $lastDate = new \DateTime($endDate);
        
        while ($currentDate <= $lastDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $this->roomModel->setRoomPriceForDate($roomId, $dateStr, $price);
            $currentDate->modify('+1 day');
        }
        
        return $this->response->setJSON(['success' => true, 'message' => 'Harga berhasil diperbarui untuk rentang tanggal yang dipilih']);
    }
    
    public function resetPriceForDate()
    {
        $roomId = $this->request->getPost('room_id');
        $date = $this->request->getPost('date');
        
        $room = $this->roomModel->find($roomId);
        
        if (!$room) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kamar tidak ditemukan']);
        }
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $room['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses ke kamar ini']);
            }
        }
        
        $db = \Config\Database::connect();
        $db->table('room_prices')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->delete();
        
        return $this->response->setJSON(['success' => true, 'message' => 'Harga berhasil direset ke harga default']);
    }

}
