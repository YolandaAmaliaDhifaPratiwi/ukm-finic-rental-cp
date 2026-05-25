<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {

        // Paksa URL pakai IP laptop
        // URL::forceRootUrl('http://192.168.1.105:8000');

            
        // Use custom Finic pagination view
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        Carbon::setLocale(app()->getLocale());

    }
}