<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'avatar',
        'reliability_score',
        'membership_level',
        'google_id'
    ];
    

    protected $hidden = [
        'password',
        'remember_token',
        'google_id'
    ];

    // ✅ FIX: Hapus 'password' => 'hashed' untuk hindari double hash
    // Hash sudah ditangani manual di controller via Hash::make()
    // atau jika ingin pakai cast, pastikan controller TIDAK pakai Hash::make()
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ========================
    // RELATIONSHIP
    // ========================
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)
            ->where('status', 'approved');
    }

    public function pendingBorrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class)
            ->where('status', 'pending');
    }

    // ========================
    // ROLE CHECK
    // ========================
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // ========================
    // ACCESSOR
    // ========================
    public function getMembershipBadgeAttribute(): string
    {
        return strtoupper($this->membership_level) . ' MEMBER';
    }

    public function getRankAttribute(): int
    {
        return self::where('role', 'member')
            ->where('reliability_score', '>', $this->reliability_score)
            ->count() + 1;
    }
}