<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use App\Models\Customer;
use App\Observers\AuditObserver;
use RateLimiter;
use Illuminate\Pagination\Paginator;
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
        Customer::observe(AuditObserver::class);
        Paginator::useBootstrapFive();
        Appointment::observe(AuditObserver::class);
        Product::observe(AuditObserver::class);
        Invoice::observe(AuditObserver::class);
        RateLimiter::for('login', function (Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });
        // Appointment::observe(...), Product::observe(...), Invoice::observe(...)
    }

}
