<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $allowedFields = ['hotel_id', 'guest_name', 'rating', 'comment', 'review_date', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getByHotelId($hotelId)
    {
        return $this->where('hotel_id', $hotelId)
            ->orderBy('review_date', 'DESC')
            ->findAll();
    }

    public function getAverageRating($hotelId)
    {
        $result = $this->selectAvg('rating')
            ->where('hotel_id', $hotelId)
            ->first();

        return round($result['rating'] ?? 0, 1);
    }
}
