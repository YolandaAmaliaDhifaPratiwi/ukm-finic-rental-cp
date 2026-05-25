<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    protected $fillable = [
        'name', 'category', 'condition', 'status',
        'serial_number', 'asset_tag', 'image', 'description'
    ];

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowing()
    {
        return $this->hasOne(Borrowing::class)->whereIn('status', ['approved'])->latest();
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    // public function getCategoryLabelAttribute(): string
    // {
    //     return match($this->category) {
    //         'camera' => 'Camera',
    //         'lens'   => 'Lens',
    //         'tripod' => 'Tripod',
    //         'lighting' => 'Lighting',
    //         default  => 'Accessory',
    //     };
    // }

    public function getCategoryLabelAttribute(): string
    {
        return __('member.category_' . $this->category);
    }

    // public function getConditionLabelAttribute(): string
    // {
    //     return match($this->condition) {
    //         'excellent'    => 'Excellent',
    //         'good'         => 'Good',
    //         'fair'         => 'Fair',
    //         'needs_repair' => 'Needs Repair',
    //         default        => $this->condition,
    //     };
    // }
    
    public function getConditionLabelAttribute(): string
    {
        return __('member.condition_' . $this->condition);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : asset('images/placeholder.png');
    }
}