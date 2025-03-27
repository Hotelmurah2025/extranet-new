<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    public function findUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function findUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    public function getUserHotels($userId)
    {
        $db = \Config\Database::connect();
        return $db->table('user_hotels')
            ->select('hotels.*')
            ->join('hotels', 'hotels.id = user_hotels.hotel_id')
            ->where('user_hotels.user_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function addHotelToUser($userId, $hotelId)
    {
        $db = \Config\Database::connect();
        return $db->table('user_hotels')->insert([
            'user_id' => $userId,
            'hotel_id' => $hotelId
        ]);
    }

    public function removeHotelFromUser($userId, $hotelId)
    {
        $db = \Config\Database::connect();
        return $db->table('user_hotels')
            ->where('user_id', $userId)
            ->where('hotel_id', $hotelId)
            ->delete();
    }
}
