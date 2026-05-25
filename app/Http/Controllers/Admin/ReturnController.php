<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ItemReturn as ReturnModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Daftar semua permintaan pengembalian
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $returns = ReturnModel::with(['borrowing.equipment', 'user'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        $counts = [
            'pending'   => ReturnModel::where('status', 'pending')->count(),
            'confirmed' => ReturnModel::where('status', 'confirmed')->count(),
            'rejected'  => ReturnModel::where('status', 'rejected')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'status', 'counts'));
    }

    /**
     * Detail pengembalian
     */
    public function show($id)
    {
        $return = ReturnModel::with(['borrowing.equipment', 'user'])->findOrFail($id);
        return view('admin.returns.show', compact('return'));
    }

    /**
     * Konfirmasi pengembalian
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $return = ReturnModel::with('borrowing')->findOrFail($id);

        DB::transaction(function () use ($return, $request) {
            $return->update([
                'status'       => 'confirmed',
                'admin_notes'  => $request->admin_notes,
                'confirmed_at' => now(),
            ]);

            // ← fix: actual_return → actual_return_date, sesuai migration
            $return->borrowing->update([
                'status'             => 'returned',
                'return_status'      => 'confirmed',
                'actual_return_date' => now(),
            ]);

            // Kembalikan stok equipment
            $return->borrowing->equipment()->increment('stock');

            // Notifikasi ke member
            DB::table('notifications')->insert([
                'id'              => \Illuminate\Support\Str::uuid(),
                'type'            => 'App\\Notifications\\ReturnConfirmed',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $return->user_id,
                'data'            => json_encode([
                    'title'     => 'Pengembalian Dikonfirmasi',
                    'message'   => "Pengembalian {$return->borrowing->equipment->name} telah dikonfirmasi admin.",
                    'return_id' => $return->id,
                    'type'      => 'return_confirmed',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('admin.returns.index')
            ->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }

    /**
     * Tolak pengembalian
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $return = ReturnModel::with('borrowing')->findOrFail($id);

        DB::transaction(function () use ($return, $request) {
            $return->update([
                'status'      => 'rejected',
                'admin_notes' => $request->admin_notes,
            ]);

            $return->borrowing->update(['return_status' => 'rejected']);

            // Notifikasi ke member
            DB::table('notifications')->insert([
                'id'              => \Illuminate\Support\Str::uuid(),
                'type'            => 'App\\Notifications\\ReturnRejected',
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id'   => $return->user_id,
                'data'            => json_encode([
                    'title'     => 'Pengembalian Ditolak',
                    'message'   => "Pengembalian {$return->borrowing->equipment->name} ditolak. Alasan: {$request->admin_notes}",
                    'return_id' => $return->id,
                    'type'      => 'return_rejected',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('admin.returns.index')
            ->with('success', 'Pengembalian berhasil ditolak.');
    }
}
