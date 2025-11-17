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
        // 1. Cek apakah pengguna sudah login
        // Jika belum login, kita lempar ke halaman login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil data pengguna yang sedang login
        $user = Auth::user();

        // 3. Loop (periksa) semua peran yang diizinkan untuk rute ini
        // "...$roles" adalah semua argumen yang kita kirim dari file web.php (contoh: 'admin', 'hrd')
        foreach ($roles as $role) {
            // 4. Jika peran pengguna SAMA DENGAN salah satu peran yang diizinkan
            if ($user->role == $role) {
                // Izinkan pengguna melanjutkan ke halaman yang dituju
                return $next($request);
            }
        }

        // 5. Jika pengguna login TAPI perannya tidak cocok
        // Kita lempar mereka ke halaman 'dashboard' (atau halaman lain yang aman)
        // abort(403); // Alternatif lain adalah menampilkan halaman "403 Forbidden"
        return redirect('/')->with('error', 'Anda tidak memiliki hak akses.');
    }
}

?>