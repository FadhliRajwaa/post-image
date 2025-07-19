<?php

namespace App\Providers;

use App\Services\ImageProcessor;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageProcessor::class, function ($app) {
            return new ImageProcessor();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
