<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // Make FormatHelper available in all views
        \Illuminate\Support\Facades\View::share('formatHelper', new FormatHelper());
        
        // Route model binding for nested resources
        // This ensures that when we have /proyek/{proyek}/tugas/{tugas}
        // the {tugas} is resolved as a Tugas model scoped to that specific Proyek
        
        // Bind Proyek model
        // $this->app['router']->model('proyek', Proyek::class);
        
        // Bind Tugas model - using the nested route scope
        // This will only find Tugas that belong to the specified Proyek
        // $this->app['router']->bind('tugas', function ($id, $route) {
        //     $proyek = $route->parameter('proyek');
        //     return Tugas::where('id', $id)
        //         ->where('proyek_id', $proyek->id)
        //         ->firstOrFail();
        // });
    }
}

