<?php

namespace App\Providers;
use App\View\Composers\FooterComposer;
use App\View\Composers\CartsComposer;
use App\View\Composers\MenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Using class based composers...
        View::composer('post.layout.footer', FooterComposer::class);
        View::composer('post.layout.header', MenuComposer::class);
        View::composer('post.layout.header', CartsComposer::class);

    }
}
