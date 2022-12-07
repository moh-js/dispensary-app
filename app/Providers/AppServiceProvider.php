<?php

namespace App\Providers;

use App\Charts\PatientVisit;
// use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // Schema::defaultStringLength(255);
        // $charts->register([
        //     PatientVisit::class
        // ]);

        Paginator::useBootstrap();
    }
}
