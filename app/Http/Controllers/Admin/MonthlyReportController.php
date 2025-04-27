<?php
namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\RequestSupply;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  // Make sure to import the Request class

class MonthlyReportController extends Controller
{
    public function monthlyReports(Request $request)
    {
        // Get the month and year from the request, or default to current month and year
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
    
        // Fetch the data for the selected month and year
        $totalRequesters = $this->getTotalRequesters($year, $month);
        $totalUsers = $this->getTotalUsers($year, $month);
    
        // Total completed withdrawals
        $totalCompletedItems = RequestSupply::where('withdrawal_status', 'completed')
                                           ->when($year, function($q) use ($year) {
                                               $q->whereYear('created_at', $year);
                                           })
                                           ->when($month, function($q) use ($month) {
                                               $q->whereMonth('created_at', $month);
                                           })
                                           ->sum('quantity');
    
        // Total return requests (all statuses)
        $totalReturns = ReturnRequest::when($year, function($q) use ($year) {
                                        $q->whereYear('created_at', $year);
                                    })
                                    ->when($month, function($q) use ($month) {
                                        $q->whereMonth('created_at', $month);
                                    })
                                    ->count();
    
        // Total completed replacements
        $totalReplacementCompleted = ReturnRequest::where('replacement_status', 'completed')
                                                ->when($year, function($q) use ($year) {
                                                    $q->whereYear('created_at', $year);
                                                })
                                                ->when($month, function($q) use ($month) {
                                                    $q->whereMonth('created_at', $month);
                                                })
                                                ->count();
    
        // Fetch all stock records
        $stocks = Stock::orderBy('item_name')->get();
        $itemNames = $stocks->pluck('item_name');
        $quantities = $stocks->pluck('stock_quantity');
    
        $reports = Stock::get();
        $requestHistory = RequestSupply::where('withdrawal_status', 'completed')->get();
    
        // Pass data to the view
        return view('admin.monthly-reports', compact(
            'itemNames',
            'quantities',
            'totalRequesters',
            'totalUsers',
            'totalCompletedItems',
            'totalReturns',
            'totalReplacementCompleted',
            'reports',
            'requestHistory'
        ));
    }
    public function getMonthlyCompletedWithdrawals(Request $request)
    {
        $query = RequestSupply::whereNotNull('completed_at')
            ->selectRaw('MONTH(completed_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month');
    
        // Filter by year (required)
        $year = $request->input('year', date('Y'));
        $query->whereYear('completed_at', $year);
    
        // Optional month filter
        if ($request->has('month')) {
            $query->whereMonth('completed_at', $request->month);
        }
    
        $results = $query->get();
    
        // Ensure all 12 months are represented
        $allMonths = collect(range(1, 12))->map(function ($month) use ($results) {
            $record = $results->firstWhere('month', $month);
            return [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'count' => $record ? $record->count : 0
            ];
        });
    
        return response()->json($allMonths);
    }

    public function getMonthlyReturns(Request $request)
{
    $query = ReturnRequest::whereNotNull('return_date')
        ->selectRaw('MONTH(return_date) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month');

    // Required year filter
    $year = $request->input('year', date('Y'));
    $query->whereYear('return_date', $year);

    // Optional month filter
    if ($request->has('month')) {
        $query->whereMonth('return_date', $request->month);
    }

    $results = $query->get();

    // Normalize data for all 12 months
    $allMonths = collect(range(1, 12))->map(function ($month) use ($results) {
        $record = $results->firstWhere('month', $month);
        return [
            'month' => $month,
            'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
            'count' => $record ? $record->count : 0
        ];
    });

    return response()->json($allMonths);
}
    // Helper methods to fetch data for the report
    private function getTotalRequesters($year, $month)
    {
        return RequestSupply::whereYear('datetime', $year)
                            ->whereMonth('datetime', $month)
                            ->distinct('user_id') // Get unique users
                            ->count();
    }
    
    private function getTotalUsers($year, $month)
    {
        return RequestSupply::whereYear('datetime', $year)
                            ->whereMonth('datetime', $month)
                            ->count();
    }
    
    private function getTotalItems($year, $month)
    {
        return RequestSupply::whereYear('datetime', $year)
                            ->whereMonth('datetime', $month)
                            ->sum('quantity');
    }   
    
    private function getTotalApproved($year, $month)
    {
        return RequestSupply::whereYear('datetime', $year)
                            ->whereMonth('datetime', $month)
                            // ->where('status', 'Approved')
                            ->count();
    }
    
}
