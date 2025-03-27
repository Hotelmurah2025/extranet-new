<?php

namespace App\Controllers;

use App\Models\ReviewModel;
use App\Models\HotelModel;
use App\Models\UserModel;

class Review extends BaseController
{
    protected $reviewModel;
    protected $hotelModel;
    protected $userModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->hotelModel = new HotelModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $role = session()->get('role');
        
        if ($role === 'admin_pusat') {
            $hotels = $this->hotelModel->findAll();
        } else {
            $hotels = $this->userModel->getUserHotels($userId);
            
            if (empty($hotels)) {
                return view('review/list', [
                    'title' => 'Daftar Ulasan',
                    'hotels' => [],
                    'noHotelAccess' => true
                ]);
            }
        }
        
        $data = [
            'title' => 'Daftar Ulasan',
            'hotels' => $hotels
        ];
        
        return view('review/list', $data);
    }
    
    public function hotelReviews($hotelId)
    {
        $hotel = $this->hotelModel->find($hotelId);
        
        if (!$hotel) {
            return redirect()->to('/review')->with('error', 'Hotel tidak ditemukan');
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
        
        $reviews = $this->reviewModel->getByHotelId($hotelId);
        $averageRating = $this->reviewModel->getAverageRating($hotelId);
        
        $data = [
            'title' => 'Ulasan Hotel: ' . $hotel['name'],
            'hotel' => $hotel,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ];
        
        return view('review/hotel_reviews', $data);
    }
    
    public function detail($reviewId)
    {
        $review = $this->reviewModel->find($reviewId);
        
        if (!$review) {
            return redirect()->to('/review')->with('error', 'Ulasan tidak ditemukan');
        }
        
        $hotel = $this->hotelModel->find($review['hotel_id']);
        
        if (session()->get('role') === 'user_hotel') {
            $userHotels = $this->userModel->getUserHotels(session()->get('user_id'));
            $hasAccess = false;
            
            foreach ($userHotels as $userHotel) {
                if ($userHotel['id'] == $review['hotel_id']) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke ulasan ini');
            }
        }
        
        $data = [
            'title' => 'Detail Ulasan',
            'review' => $review,
            'hotel' => $hotel
        ];
        
        return view('review/detail', $data);
    }
}
