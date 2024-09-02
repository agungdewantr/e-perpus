<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('can_create', function ($user,  Request $request) {
            $currentUrl = $request->getPathInfo();
            $currentMenu = \DB::table('permissions as p')
                            ->join('menus as m', 'm.id', '=', 'p.menu_id')
                            ->select('p.can_create')
                            ->where('m.url', $currentUrl)
                            ->where('p.role_id', $user->role->id)
                            ->where('m.is_active', true)
                            ->where('p.can_create', true)
                            ->orderBy('m.ordinal_number')
                            ->first();
            if($currentMenu){
                return true;
            }
            else{
                return false;
            }
        });
        Gate::define('can_update', function ($user,  Request $request) {
            $currentUrl = $request->getPathInfo();
            $currentMenu = \DB::table('permissions as p')
                            ->join('menus as m', 'm.id', '=', 'p.menu_id')
                            ->select('p.can_update')
                            ->where('m.url', $currentUrl)
                            ->where('p.role_id', $user->role->id)
                            ->where('m.is_active', true)
                            ->where('p.can_update', true)
                            ->orderBy('m.ordinal_number')
                            ->first();
            if($currentMenu){
                return true;
            }
            else{
                return false;
            }
        });
        Gate::define('can_delete', function ($user,  Request $request) {
            $currentUrl = $request->getPathInfo();
            $currentMenu = \DB::table('permissions as p')
                            ->join('menus as m', 'm.id', '=', 'p.menu_id')
                            ->select('p.can_delete')
                            ->where('m.url', $currentUrl)
                            ->where('p.role_id', $user->role->id)
                            ->where('m.is_active', true)
                            ->where('p.can_delete', true)
                            ->orderBy('m.ordinal_number')
                            ->first();
            if($currentMenu){
                return true;
            }
            else{
                return false;
            }
        });
    }
}
