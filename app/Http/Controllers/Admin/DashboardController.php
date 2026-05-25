<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInventory = Equipment::count();
        $activeBorrowings = Borrowing::where('status', 'approved')->count();
        $pendingRequests = Borrowing::where('status', 'pending')->count();
        $recentActivity = Borrowing::with(['user', 'equipment'])
            ->latest()
            ->take(5)
            ->get();
        $trendingEquipment = Equipment::withCount('borrowings as rental_count')
            ->orderByDesc('rental_count')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalInventory',
            'activeBorrowings',
            'pendingRequests',
            'recentActivity',
            'trendingEquipment'
        ));
    }
}