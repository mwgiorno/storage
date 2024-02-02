<?php

namespace App\Providers;

use App\Services\OnlyOffice\FormatManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FormatManager::class, function (Application $app) {
            $jsonFormats = Storage::disk('assets')
                ->json('onlyoffice-formats/formats.json');
        
            return new FormatManager($jsonFormats);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
