<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Helpers\FormatHelper;

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
        // Configure custom rate limiters for BoQ uploads
        RateLimiter::for('boq_upload', function ($request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        
        RateLimiter::for('boq_store', function ($request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
        
        // More relaxed limit for analysis (can be tested frequently during development)
        RateLimiter::for('boq_analyze', function ($request) {
            // Development: 30 requests per minute, Production: 5 requests per minute
            $limit = env('APP_ENV') === 'local' ? 30 : 5;
            return Limit::perMinute($limit)->by($request->user()?->id ?: $request->ip());
        });
        
        // Make FormatHelper available in all views
        \Illuminate\Support\Facades\View::share('formatHelper', new FormatHelper());
        
        // Route model binding for nested resources
        // This ensures that when we have /proyek/{proyek}/tugas/{tugas}
        // the {tugas} is resolved as a Tugas model scoped to that specific Proyek
        
        // Bind Tugas model - using the nested route scope
        // This will only find Tugas that belong to the specified Proyek
        $this->app['router']->bind('tugas', function ($value, $route) {
            // Get proyek parameter - it might be 'proyek' or might already be resolved
            $proyek = $route->parameter('proyek');
            
            // If there's no proyek parameter, just return the tugas normally
            // (for non-nested routes if any)
            if (!$proyek) {
                return Tugas::findOrFail($value);
            }
            
            // If proyek is not a model yet (it's still an ID), resolve it first
            if (!$proyek instanceof Proyek) {
                $proyek = Proyek::findOrFail($proyek);
            }
            
            // Return tugas that belongs to this proyek
            return Tugas::where('id', $value)
                ->where('proyek_id', $proyek->id)
                ->firstOrFail();
        });
        
        // Also bind 'tuga' (singular form that Laravel might use)
        $this->app['router']->bind('tuga', function ($value, $route) {
            $proyek = $route->parameter('proyek');
            
            if (!$proyek) {
                return Tugas::findOrFail($value);
            }
            
            if (!$proyek instanceof Proyek) {
                $proyek = Proyek::findOrFail($proyek);
            }
            
            return Tugas::where('id', $value)
                ->where('proyek_id', $proyek->id)
                ->firstOrFail();
        });
    }
}

