<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

use Illuminate\Pagination\Paginator; #Para la paginacion

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        #Para la Paginacion Tambien
        #Schema::defaultStringLength(191);
        #Paginator::useBootstrapFive();

        Paginator::useBootstrap();

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'es_SV', 'es', 'ES', 'es_SV.utf8');
    }
}
