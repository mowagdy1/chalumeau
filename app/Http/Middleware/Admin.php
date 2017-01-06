<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()){
            if (Auth::user()->role=='admin'){
                return $next($request);
            }
            return redirect('/')->with('message', 'You do not have permission to access the dashboard!')->with('class', 'alert-danger');
        }
        return redirect('dashboard/login')->with('message', 'You have to login to access this page!')->with('class', 'alert-danger');
    }
}
