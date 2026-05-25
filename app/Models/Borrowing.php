<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Borrowing extends Model
{
    protected $fillable = [
        'transaction_code', 'user_id', 'equipment_id',
        'borrow_date', 'return_date', 'actual_return_date',
        'purpose', 'status', 'final_condition',
        'admin_notes', 'approved_by'
    ];

    protected $casts = [
        'borrow_date'        => 'date',
        'return_date'        => 'date',
        'actual_return_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($borrowing) {
            if (empty($borrowing->transaction_code)) {
                $last = static::latest('id')->first();
                $nextId = $last ? $last->id + 1 : 1001;
                $borrowing->transaction_code = 'TRX-' . $nextId;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'approved'
            && $this->return_date->isPast()
            && !$this->actual_return_date;
    }

    public function getDaysRemainingAttribute(): int
    {
        return (int) now()->diffInDays($this->return_date, false);
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->borrow_date->diffInDays($this->return_date);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => '<span class="badge badge-pending">Menunggu</span>',
            'approved' => '<span class="badge badge-approved">Aktif</span>',
            'rejected' => '<span class="badge badge-rejected">Ditolak</span>',
            'returned' => '<span class="badge badge-returned">Dikembalikan</span>',
            'overdue'  => '<span class="badge badge-overdue">Terlambat</span>',
            default    => $this->status,
        };
    }

        public function returnData()
        {
            return $this->hasOne(\App\Models\ItemReturn::class, 'borrowing_id');
        }
}