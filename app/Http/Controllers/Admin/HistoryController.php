<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn($sq) => $sq->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('equipment', fn($sq) => $sq->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('transaction_code', 'like', '%' . $request->search . '%');
            });
        }

        // from_date = filter Tanggal Pinjam (borrow_date)
        if ($request->filled('from_date')) {
            $query->whereDate('borrow_date', $request->from_date);
        }

        // to_date = filter Tanggal Kembali (return_date)
        if ($request->filled('to_date')) {
            $query->whereDate('return_date', $request->to_date);
        }

        $borrowings   = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $totalRecords = Borrowing::count();
        $thisMonth    = Borrowing::whereMonth('created_at', now()->month)->count();

        $avgDuration = Borrowing::whereNotNull('actual_return_date')
            ->get()
            ->avg(fn($b) => $b->borrow_date->diffInDays($b->actual_return_date)) ?? 0;

        $returnedCount  = Borrowing::where('status', 'returned')->count();
        $completedCount = Borrowing::whereIn('status', ['returned', 'overdue'])->count();
        $returnRate     = $completedCount > 0
            ? round($returnedCount / $completedCount * 100, 1)
            : 100;

        return view('admin.history.index', compact(
            'borrowings', 'totalRecords', 'thisMonth', 'avgDuration', 'returnRate'
        ));
    }

    public function exportCsv(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        if ($request->filled('from_date')) {
            $query->whereDate('borrow_date', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('return_date', $request->to_date);
        }

        $borrowings = $query->orderByDesc('created_at')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="borrowing_history.csv"',
        ];

        $callback = function () use ($borrowings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['TRX Code', 'Member', 'Equipment', 'Category', 'Borrow Date', 'Return Date', 'Final Condition', 'Status']);
            foreach ($borrowings as $b) {
                fputcsv($file, [
                    $b->transaction_code,
                    $b->user->name,
                    $b->equipment->name,
                    $b->equipment->category,
                    $b->borrow_date->format('d/m/Y'),
                    $b->return_date->format('d/m/Y'),
                    $b->final_condition ?? '-',
                    $b->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}