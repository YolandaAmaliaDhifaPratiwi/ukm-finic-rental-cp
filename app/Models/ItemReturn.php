<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'borrowing_id',
        'user_id',
        'status',
        'condition_notes',
        'admin_notes',
        'photo',
        'returned_at',
        'confirmed_at',
    ];

    protected $casts = [
        'returned_at'  => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}