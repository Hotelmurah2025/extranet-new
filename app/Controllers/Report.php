<?php

namespace App\Controllers;

use App\Models\ReportModel;

class Report extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        $this->reportModel = new ReportModel();
    }

    public function index()
    {
        return redirect()->to('/report/daily');
    }

    public function daily()
    {
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $report = $this->reportModel->getDailyReport($date);

        $data = [
            'title' => 'Daily Report',
            'report' => $report,
            'date' => $date
        ];

        return view('report/daily', $data);
    }

    public function monthly()
    {
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');
        $report = $this->reportModel->getMonthlyReport($month, $year);

        $data = [
            'title' => 'Monthly Report',
            'report' => $report,
            'month' => $month,
            'year' => $year
        ];

        return view('report/monthly', $data);
    }

    public function occupancy()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $occupancyData = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $occupancyData[] = [
                'date' => $currentDate,
                'rate' => $this->reportModel->getOccupancyRate($currentDate)
            ];
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        $data = [
            'title' => 'Occupancy Report',
            'occupancyData' => $occupancyData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('report/occupancy', $data);
    }

    public function revenue()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-t');

        $db = \Config\Database::connect();
        $query = $db->table('reservations')
            ->select('DATE(check_in_date) as date, SUM(total_price) as revenue')
            ->where('check_in_date >=', $startDate)
            ->where('check_in_date <=', $endDate)
            ->where('status !=', 'cancelled')
            ->groupBy('DATE(check_in_date)')
            ->orderBy('date', 'ASC')
            ->get();

        $revenueData = $query->getResultArray();

        $data = [
            'title' => 'Revenue Report',
            'revenueData' => $revenueData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('report/revenue', $data);
    }
}
