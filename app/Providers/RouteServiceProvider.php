<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web'])
            ->namespace($this->namespace . '\\Admin')
            ->prefix('admin')
            ->as('admin.')
            ->group(base_path('routes/admin.php'));

        Route::middleware(['web', 'check-locked-period:client'])
            ->namespace($this->namespace . '\\Client')
            ->prefix('unit')
            ->as('client.')
            ->group(base_path('routes/client.php'));

        Route::middleware(['web', 'check-locked-period:client'])
            ->namespace($this->namespace . '\\Client\\Report')
            ->prefix('unit')
            ->as('client.report.')
            ->group(base_path('routes/report.php'));

        Route::middleware(['web'])
            ->namespace($this->namespace . '\\Client\\Master')
            ->prefix('unit/master')
            ->as('client.master.')
            ->group(base_path('routes/client-master.php'));

        Route::middleware('web')
            ->namespace($this->namespace . '\\General')
            ->as('general.')
            ->group(base_path('routes/general.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->namespace($this->namespace . '\\Api\\v1')
            ->as('api.')
            ->group(base_path('routes/api.php'));
    }
}
