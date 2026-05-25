<?php

return [
    'name'            => env('APP_NAME', 'UKM Finic Equipment Rental'),
    'env'             => env('APP_ENV', 'production'),
    'debug'           => (bool) env('APP_DEBUG', false),
    'url'             => env('APP_URL', 'http://localhost'),
    'asset_url'       => env('ASSET_URL'),
    'timezone'        => 'Asia/Kuala_Lumpur',
    'locale'          => 'en',
    'fallback_locale' => 'en',
    'faker_locale'    => 'id_ID',
    'key'             => env('APP_KEY'),
    'cipher'          => 'AES-256-CBC',
    'maintenance'     => ['driver' => 'file'],

    'providers' => Illuminate\Support\ServiceProvider::defaultProviders()->merge([
        App\Providers\AppServiceProvider::class,
    ])->toArray(),

    'aliases' => Illuminate\Support\Facades\Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),
];