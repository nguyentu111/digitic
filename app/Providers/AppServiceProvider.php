<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Implements\BaseRepository;
use App\Repositories\Interfaces\IBaseRepository;
use App\Repositories\Implements\ProductRepository;
use App\Repositories\Interfaces\IProductRepository;
use App\Models\Product;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       
        $this->app->singleton(IProductRepository::class, function () {
            return new ProductRepository(new Product());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
