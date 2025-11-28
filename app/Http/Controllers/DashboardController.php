<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Holiday; // Pastikan Model Holiday di-import
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data User yang sedang login
        $user = Auth::user();

        // 2. LOGIC WIDGET BARU: Ambil 3 hari libur mendatang
        // Mengambil data dari tabel holidays dimana tanggal >= hari ini
        $upcomingHolidays = Holiday::where('date', '>=', now())
                            ->orderBy('date', 'asc') // Urutkan dari yang terdekat
                            ->limit(3)               // Ambil maksimal 3 saja
                            ->get();

        // 3. Kirim data ke view dashboard
        // Pastikan nama view-nya sesuai (misal: 'dashboard' atau 'user.dashboard')
        return view('dashboard', compact('user', 'upcomingHolidays'));
    }
}