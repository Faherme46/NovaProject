<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\ExcelServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */


    public function register(): void
    {
        $this->app->register(ExcelServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // static $dbHost = null;

        // if ($dbHost === null) {
        //     $filePath = storage_path('app/db_host.txt');
        //     $dbHost = file_exists($filePath) ? trim(file_get_contents($filePath)) : env('DB_HOST', '127.0.0.1');
        // }

        // Config::set('database.connections.mariadb.host', $dbHost);



        View::composer('*', function ($view) {

            $view->with(
                [
                    'asamblea' => cache('asamblea'),
                    'registro' => cache('inRegistro'),
                    'themeId' => cache('themeId', 5)
                ]
            );
        });
    }
}
