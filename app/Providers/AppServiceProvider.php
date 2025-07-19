<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pastikan direktori posters ada dan memiliki permission yang benar
        $postersDir = storage_path('app/public/posters');
        if (!is_dir($postersDir)) {
            mkdir($postersDir, 0777, true);
        }
        
        // Pastikan direktori memiliki permission yang benar
        if (is_dir($postersDir) && substr(sprintf('%o', fileperms($postersDir)), -4) != '0777') {
            chmod($postersDir, 0777);
        }
    }
}
