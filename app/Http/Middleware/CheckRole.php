<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini di-import
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!Auth::check()) {
        return redirect('login');
    }

    $user = Auth::user();

    foreach ($roles as $role) {
        if ($user->role == $role) {
            return $next($request);
        }
    }

    // Simple redirect ke dashboard dengan error message
    return redirect('/dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
}
}

?>