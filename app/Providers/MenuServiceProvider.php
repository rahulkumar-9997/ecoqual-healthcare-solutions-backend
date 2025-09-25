<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }    
    public function boot()
    {
        View::composer('backend.layouts.sidebar', function ($view) {
            $menus = Menu::whereNull('parent_id')
                ->with('children')
                ->get();
            $view->with('menus', $menus);
        });
    }
}
