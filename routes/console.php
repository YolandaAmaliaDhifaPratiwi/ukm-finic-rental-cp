<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-mark overdue borrowings daily
Schedule::call(function () {
    \App\Models\Borrowing::where('status', 'approved')
        ->where('return_date', '<', now())
        ->update(['status' => 'overdue']);
})->daily()->name('mark-overdue-borrowings');