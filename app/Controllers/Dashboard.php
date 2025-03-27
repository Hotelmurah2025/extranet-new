<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\ReservationModel;
use App\Models\GuestModel;
use App\Models\HousekeepingModel;
use App\Models\ReportModel;
use App\Models\HotelModel;
use App\Models\UserModel;
use App\Models\ReviewModel;

class Dashboard extends BaseController
{
    protected $roomModel;
    protected $reservationModel;
    protected $guestModel;
    protected $housekeepingModel;
    protected $reportModel;
    protected $hotelModel;
    protected $userModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->roomModel = new RoomModel();
        $this->reservationModel = new ReservationModel();
        $this->guestModel = new GuestModel();
        $this->housekeepingModel = new HousekeepingModel();
        $this->reportModel = new ReportModel();
        $this->hotelModel = new HotelModel();
        $this->userModel = new UserModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');
        
        if ($role === 'admin_pusat') {
            $hotels = $this->hotelModel->findAll();
        } else {
            $hotels = $this->userModel->getUserHotels($userId);
            
            if (empty($hotels)) {
                return view('dashboard', [
                    'title' => 'Dashboard',
                    'hotels' => [],
                    'noHotelAccess' => true
                ]);
            }
        }
        
        $hotelIds = array_column($hotels, 'id');
        $hotelStats = [];
        $totalBookings = 0;
        $totalOccupancyRate = 0;
        $totalReviewRating = 0;
        $hotelCount = count($hotels);
        
        foreach ($hotels as $hotel) {
            $rooms = $this->roomModel->getRoomsByHotelId($hotel['id']);
            $roomIds = array_column($rooms, 'id');
            $roomCount = count($rooms);
            
            if ($roomCount === 0) {
                continue;
            }
            
            $bookings = $this->reservationModel->select('COUNT(*) as total')
                ->whereIn('room_id', $roomIds)
                ->where('SUBSTRING(check_in_date, 1, 7)', $currentMonth)
                ->first();
            
            $hotelBookings = $bookings ? $bookings['total'] : 0;
            $totalBookings += $hotelBookings;
            
            $occupiedRooms = $this->roomModel->where('hotel_id', $hotel['id'])
                ->where('status', 'occupied')
                ->countAllResults();
            
            $occupancyRate = $roomCount > 0 ? ($occupiedRooms / $roomCount) * 100 : 0;
            $totalOccupancyRate += $occupancyRate;
            
            $averageRating = $this->reviewModel->getAverageRating($hotel['id']);
            $totalReviewRating += $averageRating;
            
            $recentReviews = $this->reviewModel->where('hotel_id', $hotel['id'])
                ->orderBy('review_date', 'DESC')
                ->limit(5)
                ->findAll();
            
            $hotelStats[$hotel['id']] = [
                'hotel' => $hotel,
                'roomCount' => $roomCount,
                'bookings' => $hotelBookings,
                'occupancyRate' => round($occupancyRate, 1),
                'averageRating' => $averageRating,
                'recentReviews' => $recentReviews
            ];
        }
        
        $averageOccupancyRate = $hotelCount > 0 ? $totalOccupancyRate / $hotelCount : 0;
        $averageReviewRating = $hotelCount > 0 ? $totalReviewRating / $hotelCount : 0;
        
        $data = [
            'title' => 'Dashboard',
            'hotels' => $hotels,
            'hotelStats' => $hotelStats,
            'totalBookings' => $totalBookings,
            'averageOccupancyRate' => round($averageOccupancyRate, 1),
            'averageReviewRating' => round($averageReviewRating, 1),
            'currentMonth' => date('F Y'),
            'role' => $role
        ];
        
        return view('dashboard', $data);
    }
}
