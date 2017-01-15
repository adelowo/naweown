<?php

namespace Naweown\Providers;

use Naweown\Category;
use Naweown\Item;
use Naweown\Token;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Naweown\User;

class RouteServiceProvider extends ServiceProvider
{

    protected $namespace = 'Naweown\Http\Controllers';

    public function boot()
    {
        parent::boot();

        Route::bind("token", function (string $token) {
            return Token::findByToken($token);
        });

        Route::bind("id", function (int $id) {
            return Item::findOrFail($id);
        });

        Route::bind("category", function(string $cat){
           return Category::whereSlug($cat)->firstOrFail();
        });

        Route::bind("moniker", function (string $moniker) {
           return User::findByMoniker($moniker);
        });
    }

    public function map()
    {
        $this->mapRoutes();
    }

    protected function mapRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }
}
