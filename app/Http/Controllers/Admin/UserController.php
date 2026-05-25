<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member')->withCount('borrowings');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('student_id', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->membership) {
            $query->where('membership_level', $request->membership);
        }

        if ($request->sort === 'score') {
            $query->orderByDesc('reliability_score');
        } elseif ($request->sort === 'borrowings') {
            $query->orderByDesc('borrowings_count');
        } else {
            $query->latest();
        }

        $users       = $query->paginate(10)->withQueryString();
        $totalUsers  = User::where('role', 'member')->count();
        $goldCount   = User::where('membership_level', 'gold')->count();
        $silverCount = User::where('membership_level', 'silver')->count();
        $bronzeCount = User::where('membership_level', 'bronze')->count();
        $avgScore    = User::where('role', 'member')->avg('reliability_score');

        return view('admin.users.index', compact(
            'users', 'totalUsers', 'goldCount', 'silverCount', 'bronzeCount', 'avgScore'
        ));
    }

    public function show(User $user)
    {
        $user->loadCount('borrowings');
        $borrowings = $user->borrowings()->with('equipment')->latest()->get();
        $activeBorrowings = $borrowings->where('status', 'approved');
        $returnedBorrowings = $borrowings->where('status', 'returned');

        return view('admin.users.show', compact('user', 'borrowings', 'activeBorrowings', 'returnedBorrowings'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'              => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email,'.$user->id,
            'student_id'        => 'nullable|string|max:20',
            'membership_level'  => 'required|in:bronze,silver,gold',
            'reliability_score' => 'required|integer|min:0|max:100',
        ]);

        $user->update($request->only('name', 'email', 'student_id', 'membership_level', 'reliability_score'));

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.users.show', $user)->with('success', 'Password berhasil direset.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
