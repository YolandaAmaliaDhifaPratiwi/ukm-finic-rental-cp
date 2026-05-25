<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Equipment, Borrowing, User};

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalInventory   = Equipment::count();
        $activeBorrowings = Borrowing::where('status', 'approved')->count();
        $pendingRequests  = Borrowing::where('status', 'pending')->count();

        // Monthly stats for chart (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $label  = $date->format('M');
            $req    = Borrowing::whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month)->count();
            $done   = Borrowing::whereYear('created_at', $date->year)
                               ->whereMonth('created_at', $date->month)
                               ->whereIn('status', ['returned', 'approved'])->count();
            $monthlyStats[] = ['month' => $label, 'requests' => $req, 'completed' => $done];
        }

        // Category distribution
        $categoryStats = Borrowing::join('equipment', 'borrowings.equipment_id', '=', 'equipment.id')
            ->selectRaw('equipment.category, COUNT(*) as count')
            ->groupBy('equipment.category')
            ->pluck('count', 'category');

        // Trending equipment
        $trendingEquipment = Equipment::withCount([
            'borrowings as rental_count' => fn($q) => $q->whereIn('status', ['approved', 'returned'])
        ])->orderByDesc('rental_count')->limit(5)->get();

        // Recent activity
        $recentActivity = Borrowing::with(['user', 'equipment'])
            ->orderByDesc('created_at')->limit(4)->get();

        // Alerts
        $overdueItems = Borrowing::with(['user', 'equipment'])
            ->where('status', 'approved')
            ->where('return_date', '<', now())->get();

        return view('admin.analytics.index', compact(
            'totalInventory', 'activeBorrowings', 'pendingRequests',
            'monthlyStats', 'categoryStats', 'trendingEquipment',
            'recentActivity', 'overdueItems'
        ));
    }
}