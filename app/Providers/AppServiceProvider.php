<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
        View::composer('*', function ($view) {
            $notifCount = 0;
            $notifBarang = [];

            try {
                if (Schema::hasTable('barang')) {
                    $notifCount = DB::table('barang')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

                    $notifBarang = DB::table('barang')
                        ->whereDate('created_at', Carbon::today())
                        ->latest()
                        ->take(3)
                        ->get();
                }
            } catch (\Exception $e) {
                // Diamkan jika database belum siap
            }

            $view->with([
                'notifCount' => $notifCount,
                'notifBarang' => $notifBarang
            ]);
        });
    }
}