<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Models\User;
use App\Notifications\BorrowingRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Filter: Category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter: Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter: Status (available / borrowed)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: Condition (excellent / good / fair)
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Sort
        $sort = $request->get('sort', 'name');
        match($sort) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'available'  => $query->orderByRaw("CASE WHEN status = 'available' THEN 0 ELSE 1 END")->orderBy('name'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('name', 'asc'),
        };

        $equipment = $query->get();
        $available = Equipment::where('status', 'available')->count();
        $total     = Equipment::count();

        return view('member.equipment.index', compact('equipment', 'available', 'total'));
    }

    public function showBorrowForm(Equipment $equipment)
    {
        abort_if(!$equipment->isAvailable(), 403, 'Equipment is not available.');
        return view('member.equipment.borrow', compact('equipment'));
    }

    public function submitBorrow(Request $request, Equipment $equipment)
    {
        abort_if(!$equipment->isAvailable(), 403, 'Equipment is not available.');

        $data = $request->validate([
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'purpose'     => 'required|string|max:1000',
        ]);

        // Cek apakah user sudah punya request aktif untuk alat ini
        $existing = Borrowing::where('user_id', Auth::id())
            ->where('equipment_id', $equipment->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['equipment' => 'You already have a pending or active request for this equipment.']);
        }

        // Buat data peminjaman
        $borrowing = Borrowing::create([
            'user_id'      => Auth::id(),
            'equipment_id' => $equipment->id,
            'borrow_date'  => $data['borrow_date'],
            'return_date'  => $data['return_date'],
            'purpose'      => $data['purpose'],
            'status'       => 'pending',
        ]);

        // ✅ Kirim notifikasi ke SEMUA admin
        $borrowing->load('equipment'); // pastikan relasi ter-load
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new BorrowingRequestNotification(Auth::user(), $borrowing));
        }

        return redirect()->route('member.dashboard')
            ->with('success', 'Borrowing request submitted! Waiting for admin approval.');
    }
}