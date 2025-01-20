<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Базовый путь для определения маршрутов.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Определите маршруты для приложения.
     *
     * @return void
     */
    public function boot()
    {
        $this->map();
    }

    /**
     * Регистрируйте любые маршруты для вашего приложения.
     *
     * @return void
     */
    public function map()
    {
        Route::prefix('api')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
    }
}
