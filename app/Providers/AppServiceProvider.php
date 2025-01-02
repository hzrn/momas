<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Use Bootstrap 5 for pagination
        Paginator::useBootstrapFive();

        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        ini_set('upload_max_filesize', env('UPLOAD_MAX_FILESIZE', '2M'));
        ini_set('post_max_size', env('POST_MAX_SIZE', '8M'));
    }

}
