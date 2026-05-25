<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Borrowing, Equipment, User};
use App\Notifications\BorrowingApprovedNotification;
use App\Notifications\BorrowingRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('equipment', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $borrowings    = $query->orderByDesc('created_at')->paginate(10);
        $pendingCount  = Borrowing::where('status', 'pending')->count();
        $approvedToday = Borrowing::where('status', 'approved')
                            ->whereDate('updated_at', today())->count();
        $urgentCount   = Borrowing::where('status', 'pending')
                            ->where('borrow_date', '<=', now()->addDays(2))->count();

        return view('admin.borrowing.index', compact(
            'borrowings', 'pendingCount', 'approvedToday', 'urgentCount'
        ));
    }

    /**
     * Detail peminjaman.
     * - AJAX → return JSON untuk notif modal
     * - Normal → return Blade view
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'equipment', 'approver', 'returnData.user']);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id'               => $borrowing->id,
                'transaction_code' => $borrowing->transaction_code,
                'status'           => $borrowing->status,
                'user_name'        => $borrowing->user->name,
                'user_email'       => $borrowing->user->email,
                'user_student_id'  => $borrowing->user->student_id ?? '-',
                'equipment_name'     => $borrowing->equipment->name,
                'equipment_category' => ucfirst($borrowing->equipment->category ?? ''),
                'equipment_condition'=> ucfirst($borrowing->equipment->condition ?? '-'),
                'borrow_date'      => $borrowing->borrow_date instanceof \Carbon\Carbon ? $borrowing->borrow_date->format('d M Y') : '-',
                'return_date'      => $borrowing->return_date instanceof \Carbon\Carbon ? $borrowing->return_date->format('d M Y') : '-',
                'duration_days'    => $borrowing->duration_days ?? '-',
                'created_at'       => $borrowing->created_at->format('d M Y, H:i'),
                'purpose'          => $borrowing->purpose ?? 'Tidak ada keterangan.',
                'admin_notes'      => $borrowing->admin_notes ?? null,
            ]);
        }

        return view('admin.borrowing.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        // Jika sudah diproses, redirect dengan pesan info (bukan error 422)
        if ($borrowing->status !== 'pending') {
            return back()->with('info', "Request {$borrowing->transaction_code} sudah diproses sebelumnya (status: {$borrowing->status}).");
        }

        $borrowing->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
        ]);

        $borrowing->equipment->update(['status' => 'borrowed']);

        $borrowing->load('equipment');
        $borrowing->user->notify(new BorrowingApprovedNotification($borrowing));

        return back()->with('success', "Request {$borrowing->transaction_code} approved!");
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        // Jika sudah diproses, redirect dengan pesan info (bukan error 422)
        if ($borrowing->status !== 'pending') {
            return back()->with('info', "Request {$borrowing->transaction_code} sudah diproses sebelumnya (status: {$borrowing->status}).");
        }

        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        $borrowing->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
        ]);

        $borrowing->load('equipment');
        $borrowing->user->notify(new BorrowingRejectedNotification($borrowing));

        return back()->with('success', "Request {$borrowing->transaction_code} rejected.");
    }

    public function rejectReturn(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $return = $borrowing->returnData()->where('status', 'pending')->firstOrFail();

        \Illuminate\Support\Facades\DB::transaction(function () use ($return, $borrowing, $request) {
            $return->update([
                'status'      => 'rejected',
                'admin_notes' => $request->admin_notes,
            ]);

            $borrowing->update(['return_status' => 'rejected']);

            \Illuminate\Support\Facades\DB::table('notifications')->insert([
                'id'              => \Illuminate\Support\Str::uuid(),
                'type'            => 'App\\Notifications\\ReturnRejected',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $return->user_id,
                'data'            => json_encode([
                    'title'        => 'Pengembalian Ditolak',
                    'message'      => "Pengembalian \"{$borrowing->equipment->name}\" ditolak. Alasan: {$request->admin_notes}",
                    'return_id'    => $return->id,
                    'borrowing_id' => $borrowing->id,
                    'type'         => 'return_rejected',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return back()->with('success', "Pengembalian ditolak dan notifikasi telah dikirim ke member.");
    }

    /**
     * Menandai equipment sudah dikembalikan.
     */
    public function returnEquipment(Request $request, Borrowing $borrowing)
    {
        $data = $request->validate([
            'final_condition'    => 'required|in:excellent,good,minor_scratches,needs_repair',
            'actual_return_date' => 'required|date',
        ]);

        $borrowing->update([
            'status'             => 'returned',
            'final_condition'    => $data['final_condition'],
            'actual_return_date' => $data['actual_return_date'],
        ]);

        $newCondition = match($data['final_condition']) {
            'excellent'       => 'excellent',
            'good'            => 'good',
            'minor_scratches' => 'fair',
            'needs_repair'    => 'needs_repair',
        };

        $borrowing->equipment->update([
            'status'    => $data['final_condition'] === 'needs_repair' ? 'maintenance' : 'available',
            'condition' => $newCondition,
        ]);

        return redirect()->route('admin.borrowing.index')
            ->with('success', "Equipment {$borrowing->equipment->name} marked as returned!");
    }
}