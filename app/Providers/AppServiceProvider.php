<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        Paginator::useBootstrapFive();

        Str::macro('limitText', function ($text, $limit = 100, $end = '...') {
            if (mb_strwidth($text, 'UTF-8') <= $limit) {
                return $text;
            }
    
            return rtrim(mb_strimwidth($text, 0, $limit, '', 'UTF-8')).$end;
        });
    
    }
}
