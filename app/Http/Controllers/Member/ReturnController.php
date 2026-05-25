<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ItemReturn;
use App\Models\User;
use App\Notifications\ReturnSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeBorrowings = Borrowing::with('equipment')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->where('return_status', 'none')
            ->orderBy('return_date', 'asc')
            ->get();

        $pendingReturns = ItemReturn::with('borrowing.equipment')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $returnHistory = ItemReturn::with('borrowing.equipment')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'rejected'])
            ->latest()
            ->paginate(5);

        return view('member.returns.index', compact(
            'activeBorrowings',
            'pendingReturns',
            'returnHistory'
        ));
    }

    public function create($borrowingId)
    {
        $user = Auth::user();

        $borrowing = Borrowing::with('equipment')
            ->where('id', $borrowingId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->where('return_status', 'none')
            ->firstOrFail();

        return view('member.returns.create', compact('borrowing'));
    }

    public function store(Request $request, $borrowingId)
    {
        $request->validate([
            'condition_notes' => 'required|string|max:1000',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'condition_notes.required' => 'The item condition field must be filled out.',
            'photo.image'              => 'File must be an image.',
            'photo.max'                => 'The photo size must not exceed 5MB.',
        ]);

        $user = Auth::user();

        $borrowing = Borrowing::with('equipment')
            ->where('id', $borrowingId)
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'borrowed'])
            ->where('return_status', 'none')
            ->firstOrFail();

        DB::transaction(function () use ($request, $borrowing, $user) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('returns/photos', 'public');
            }

            // FIXED: Simpan return dan tangkap object-nya langsung
            $itemReturn = ItemReturn::create([
                'borrowing_id'    => $borrowing->id,
                'user_id'         => $user->id,
                'status'          => 'pending',
                'condition_notes' => $request->condition_notes,
                'photo'           => $photoPath,
                'returned_at'     => now(),
            ]);

            $borrowing->update(['return_status' => 'pending']);

            // FIXED: Pass $itemReturn langsung ke notifikasi agar return_id tidak null
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(
                    new ReturnSubmittedNotification($user, $borrowing, $itemReturn)
                );
            }
        });

        return redirect()->route('member.returns.index')
            ->with('success', 'Your request to return your device has been successfully submitted. Please wait for confirmation from the admin.');
    }

    public function show($returnId)
    {
        $return = ItemReturn::with('borrowing.equipment')
            ->where('user_id', Auth::id())
            ->findOrFail($returnId);

        return view('member.returns.show', compact('return'));
    }
}