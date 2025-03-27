<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    public function getDailyReport($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $db = \Config\Database::connect();
        
        $checkIns = $db->table('reservations')
            ->select('COUNT(*) as count')
            ->where('DATE(check_in_date)', $date)
            ->where('reservations.status', 'checked_in')
            ->get()
            ->getRow()
            ->count;
        
        $checkOuts = $db->table('reservations')
            ->select('COUNT(*) as count')
            ->where('DATE(check_out_date)', $date)
            ->where('reservations.status', 'checked_out')
            ->get()
            ->getRow()
            ->count;
        
        $revenue = $db->table('reservations')
            ->select('SUM(total_price) as total')
            ->where('DATE(check_in_date)', $date)
            ->where('reservations.status !=', 'cancelled')
            ->get()
            ->getRow()
            ->total;
        
        $occupancyRate = $this->getOccupancyRate($date);
        
        return [
            'date' => $date,
            'check_ins' => $checkIns,
            'check_outs' => $checkOuts,
            'revenue' => $revenue ? $revenue : 0,
            'occupancy_rate' => $occupancyRate
        ];
    }
    
    public function getMonthlyReport($month = null, $year = null)
    {
        if (!$month) {
            $month = date('m');
        }
        
        if (!$year) {
            $year = date('Y');
        }
        
        $db = \Config\Database::connect();
        
        $checkIns = $db->table('reservations')
            ->select('COUNT(*) as count')
            ->where('MONTH(check_in_date)', $month)
            ->where('YEAR(check_in_date)', $year)
            ->where('reservations.status !=', 'cancelled')
            ->get()
            ->getRow()
            ->count;
        
        $revenue = $db->table('reservations')
            ->select('SUM(total_price) as total')
            ->where('MONTH(check_in_date)', $month)
            ->where('YEAR(check_in_date)', $year)
            ->where('reservations.status !=', 'cancelled')
            ->get()
            ->getRow()
            ->total;
        
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $occupancyRates = [];
        $currentDate = $startDate;
        
        while ($currentDate <= $endDate) {
            $occupancyRates[] = $this->getOccupancyRate($currentDate);
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        $avgOccupancyRate = count($occupancyRates) > 0 ? array_sum($occupancyRates) / count($occupancyRates) : 0;
        
        return [
            'month' => $month,
            'year' => $year,
            'check_ins' => $checkIns,
            'revenue' => $revenue ? $revenue : 0,
            'avg_occupancy_rate' => $avgOccupancyRate
        ];
    }
    
    public function getOccupancyRate($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $db = \Config\Database::connect();
        
        $totalRooms = $db->table('rooms')->countAllResults();
        
        if ($totalRooms === 0) {
            return 0;
        }
        
        $occupiedRooms = $db->table('reservations')
            ->where('check_in_date <=', $date)
            ->where('check_out_date >', $date)
            ->where('reservations.status', 'checked_in')
            ->countAllResults();
        
        return ($occupiedRooms / $totalRooms) * 100;
    }
}
