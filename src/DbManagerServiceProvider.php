<?php

namespace PreciousGariya\DbManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use PDO;

class DbManagerServiceProvider extends ServiceProvider
{


    public function boot()
    {
        require_once __DIR__ . '/config/helpers.php';

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'db_manager');

  

        config([
            'database.connections.root_access' => [
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'forge'),
                'username' => '',
                'password' => '',
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
        ]);


    }

    public function register()
    {
    }
}
