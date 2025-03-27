<?php

namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
    protected $table = 'hotels';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'address', 'location', 'description', 'facilities', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAll()
    {
        return $this->findAll();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function getHotelImages($hotelId)
    {
        $db = \Config\Database::connect();
        return $db->table('hotel_images')
            ->where('hotel_id', $hotelId)
            ->get()
            ->getResultArray();
    }

    public function getHotelPrimaryImage($hotelId)
    {
        $db = \Config\Database::connect();
        $image = $db->table('hotel_images')
            ->where('hotel_id', $hotelId)
            ->where('is_primary', 1)
            ->get()
            ->getRowArray();

        if (!$image) {
            $image = $db->table('hotel_images')
                ->where('hotel_id', $hotelId)
                ->get()
                ->getRowArray();
        }

        return $image ? $image['image_path'] : null;
    }

    public function getHotelRooms($hotelId)
    {
        $db = \Config\Database::connect();
        return $db->table('rooms')
            ->where('hotel_id', $hotelId)
            ->get()
            ->getResultArray();
    }

    public function getHotelReviews($hotelId)
    {
        $db = \Config\Database::connect();
        return $db->table('reviews')
            ->where('hotel_id', $hotelId)
            ->orderBy('review_date', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAverageRating($hotelId)
    {
        $db = \Config\Database::connect();
        $result = $db->table('reviews')
            ->selectAvg('rating')
            ->where('hotel_id', $hotelId)
            ->get()
            ->getRowArray();

        return round($result['rating'] ?? 0, 1);
    }
}
