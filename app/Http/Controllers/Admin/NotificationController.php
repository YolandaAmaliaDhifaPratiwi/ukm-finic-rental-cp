<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Halaman penuh daftar notifikasi admin.
     * Otomatis tandai semua sebagai dibaca saat halaman dibuka.
     */
    public function page()
    {
        $user = Auth::user();

        // Tandai semua sebagai sudah dibaca saat masuk halaman
        $user->unreadNotifications->markAsRead();

        $notifications = $user->notifications()->latest()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Ambil semua notifikasi admin (JSON untuk AJAX — dipakai badge polling).
     */
    public function index()
    {
        $user          = Auth::user();
        $notifications = $user->notifications()->latest()->take(20)->get();
        $unreadCount   = $user->unreadNotifications()->count();

        $items = $notifications->map(function ($n) {
            $data = $n->data;
            return [
                'id'           => $n->id,
                'title'        => $data['title']        ?? 'Notifikasi',
                'message'      => $data['message']       ?? '',
                'type'         => $data['type']          ?? 'info',
                'return_id'    => $data['return_id']     ?? null,
                'borrowing_id' => $data['borrowing_id']  ?? null,
                'read'         => !is_null($n->read_at),
                'time'         => $n->created_at->diffForHumans(),
                'created_at'   => $n->created_at->toISOString(),
            ];
        });

        return response()->json([
            'notifications' => $items,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Detail satu notifikasi (JSON) — dipakai oleh halaman notifikasi saat klik item.
     */
    public function show($id)
    {
        $user         = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();

        // Tandai dibaca saat dibuka
        $notification->markAsRead();

        $data = $notification->data;

        // Jika ada borrowing_id, ambil detail borrowing
        $borrowingDetail = null;
        if (!empty($data['borrowing_id'])) {
            $borrowing = \App\Models\Borrowing::with(['user', 'equipment', 'approver'])
                ->find($data['borrowing_id']);

            if ($borrowing) {
                $borrowingDetail = [
                    'id'               => $borrowing->id,
                    'transaction_code' => $borrowing->transaction_code,
                    'status'           => $borrowing->status,
                    'user_name'        => $borrowing->user->name,
                    'user_email'       => $borrowing->user->email,
                    'user_student_id'  => $borrowing->user->student_id ?? '-',
                    'equipment_name'   => $borrowing->equipment->name,
                    'equipment_category' => ucfirst($borrowing->equipment->category ?? ''),
                    'equipment_condition'=> ucfirst($borrowing->equipment->condition ?? '-'),
                    'borrow_date'      => $borrowing->borrow_date?->format('d M Y') ?? '-',
                    'return_date'      => $borrowing->return_date?->format('d M Y') ?? '-',
                    'duration_days'    => $borrowing->duration_days ?? '-',
                    'created_at'       => $borrowing->created_at->format('d M Y, H:i'),
                    'purpose'          => $borrowing->purpose ?? null,
                    'admin_notes'      => $borrowing->admin_notes ?? null,
                    'link'             => route('admin.borrowing.show', $borrowing->id),
                ];
            }
        }

        // Jika ada return_id, ambil detail pengembalian
        $returnDetail = null;
        if (!empty($data['return_id'])) {
            $return = \App\Models\ItemReturn::with(['borrowing.equipment', 'user'])
                ->find($data['return_id']);

            if ($return) {
                $returnDetail = [
                    'id'              => $return->id,
                    'status'          => $return->status,
                    'user_name'       => $return->user->name ?? '-',
                    'user_email'      => $return->user->email ?? '-',
                    'equipment_name'  => $return->borrowing->equipment->name ?? '-',
                    'equipment_category' => ucfirst($return->borrowing->equipment->category ?? '-'),
                    'transaction_code'=> $return->borrowing->transaction_code ?? '-',
                    'condition_notes' => $return->condition_notes ?? null,
                    'admin_notes'     => $return->admin_notes ?? null,
                    'returned_at'     => $return->returned_at?->format('d M Y, H:i') ?? '-',
                    'confirmed_at'    => $return->confirmed_at?->format('d M Y, H:i') ?? null,
                    'created_at'      => $return->created_at->format('d M Y, H:i'),
                    'photo'           => $return->photo ?? null,
                    'link'            => route('admin.borrowing.show', $return->borrowing_id),
                ];
            }
        }

        return response()->json([
            'id'              => $notification->id,
            'title'           => $data['title']   ?? 'Notifikasi',
            'message'         => $data['message']  ?? '',
            'type'            => $data['type']     ?? 'info',
            'time'            => $notification->created_at->diffForHumans(),
            'created_at'      => $notification->created_at->format('d M Y, H:i'),
            'read'            => !is_null($notification->read_at),
            'borrowing'       => $borrowingDetail,
            'return'          => $returnDetail,
        ]);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead(Request $request, $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah ditandai sebagai dibaca.',
        ]);
    }

    /**
     * Hapus satu notifikasi.
     */
    public function destroy($id)
    {
        Auth::user()
            ->notifications()
            ->where('id', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Hapus semua notifikasi yang sudah dibaca.
     */
    public function clearRead()
    {
        Auth::user()
            ->notifications()
            ->whereNotNull('read_at')
            ->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Hitung jumlah notifikasi yang belum dibaca (polling).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}