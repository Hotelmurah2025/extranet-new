<?php

namespace App\Models;

use CodeIgniter\Model;

class HotelImageModel extends Model
{
    protected $table = 'hotel_images';
    protected $primaryKey = 'id';
    protected $allowedFields = ['hotel_id', 'image_path', 'is_primary', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getByHotelId($hotelId)
    {
        return $this->where('hotel_id', $hotelId)->findAll();
    }

    public function setPrimaryImage($imageId, $hotelId)
    {
        $this->where('hotel_id', $hotelId)->set('is_primary', 0)->update();
        return $this->update($imageId, ['is_primary' => 1]);
    }
}
