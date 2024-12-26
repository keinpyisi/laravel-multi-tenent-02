<?php

namespace App\Providers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Http\Interfaces\OrderInterface;
use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\OrderRepository;
use App\Console\Commands\LinkTenantStorageCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('ja');
        Auth::resolved(function ($auth) {
            $auth->viaRequest('tenants', function ($request) {
                if ($userId = $request->session()->get('tenant_user_id')) {
                    return \App\Models\Tenant\User::find($userId);
                }
                return null;
            });
        });
        //
        if ($this->app->runningInConsole()) {
            $this->commands([
                LinkTenantStorageCommand::class,
            ]);
        }

         // admin レイアウトの登録
         Blade::component('admin.layouts.app', 'app-layout');

         // admin コンポーネントの名前空間設定
         Blade::componentNamespace('App\\View\\Components\\Admin', 'admin');
 
         // admin ビューの名前空間追加
         View::addNamespace('admin', resource_path('views/admin'));
 
         // admin コンポーネントのパスを指定
         Blade::componentNamespace('', 'admin');
         // tenant レイアウトの登録
         Blade::component('tenant.layouts.app', 'tenant-layout');
 
         // tenant コンポーネントの名前空間設定
         Blade::componentNamespace('App\\View\\Components\\Tenant', 'tenant');
 
         // tenant ビューの名前空間追加
         View::addNamespace('tenant', resource_path('views/tenant'));
 
         // tenant コンポーネントのパスを指定
         Blade::componentNamespace('', 'tenant');
 
         Blade::component('tenant.components.sidebar', 'tenant-sidebar');
         Blade::component('tenant.components.footer', 'tenant-footer');
         Blade::component('tenant.components.header', 'tenant-header');
        //
    }
}
