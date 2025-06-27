<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        // Share categories with all views
        View::composer('*', function ($view) {
            try {
                $categories = Category::whereNull('parent_id')
                    ->with('children')
                    ->orderBy('name', 'ASC')
                    ->where('status', 1)
                    ->get();
            } catch (\Exception $e) {
                $categories = collect([]); // Empty collection if table doesn't exist or other errors
            }
            $view->with('categories', $categories);
        });
    }
}
