<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $currentUrl = $request->getPathInfo();
        $currentMenu = \DB::table('permissions as p')
                                ->join('menus as m', 'm.id', '=', 'p.menu_id')
                                ->select('m.nama', 'm.route', 'm.url', 'm.icon', 'm.key_nama', 'm.tipe_menu')
                                ->where('m.url', $currentUrl)
                                ->where('p.role_id', $user->role->id)
                                ->where('m.is_active', true)
                                ->where('p.can_read', true)
                                ->orderBy('m.ordinal_number')
                                ->first();
        if (!$currentMenu) {
            dd('Tidak Diizinikan!');
            return redirect()->route('halaman_tidak_diizinkan');
        }

        return $next($request);
    }
}
