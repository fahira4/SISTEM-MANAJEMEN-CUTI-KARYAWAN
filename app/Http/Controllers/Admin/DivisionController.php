<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\User;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::with(['leader', 'members'])->get(); 
        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ Hanya ambil ketua divisi yang BELUM memiliki divisi
        $availableLeaders = User::where('role', 'ketua_divisi')
                               ->whereNull('division_id')
                               ->get();
        
        return view('admin.divisions.create', compact('availableLeaders'));
    }


    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        // ✅ Validasi custom: pastikan ketua divisi belum memimpin divisi lain
        $leader = User::find($request->leader_id);
        if ($leader->division_id) {
            return back()->withInput()->withErrors([
                'leader_id' => 'Ketua divisi ini sudah memimpin divisi lain: ' . ($leader->division->name ?? 'Unknown Division')
            ]);
        }

        Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        return redirect()->route('admin.divisions.index')
                         ->with('success', 'Divisi berhasil dibuat.');
    }

    public function show(string $id)
    {
        //
    }

    
public function edit(Division $division)
    {
        // ✅ Untuk edit: tampilkan ketua divisi yang available + current leader
        $availableLeaders = User::where('role', 'ketua_divisi')
                               ->where(function($query) use ($division) {
                                   $query->whereNull('division_id')
                                         ->orWhere('id', $division->leader_id); // Include current leader
                               })
                               ->get();
        
        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }

    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        // ✅ Validasi custom: pastikan ketua divisi baru belum memimpin divisi lain
        if ($request->leader_id != $division->leader_id) {
            $newLeader = User::find($request->leader_id);
            if ($newLeader->division_id && $newLeader->division_id != $division->id) {
                return back()->withInput()->withErrors([
                    'leader_id' => 'Ketua divisi ini sudah memimpin divisi lain: ' . ($newLeader->division->name ?? 'Unknown Division')
                ]);
            }
        }

        $division->update([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        return redirect()->route('admin.divisions.index')
                         ->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Division $division)
{
    // Validasi: Hanya admin yang bisa hapus
    if (auth()->user()->role !== 'admin') {
        return redirect()->route('admin.divisions.index')
                         ->with('error', 'Hanya Admin yang dapat menghapus divisi.');
    }

    // Set division_id semua anggota menjadi null
    $division->members()->update(['division_id' => null]);

    $division->delete();

    return redirect()->route('admin.divisions.index')
                     ->with('success', 'Divisi berhasil dihapus. Semua anggota telah dikeluarkan dari divisi.');
}

    public function showMembers(Division $division)
    {
        // 1. Ambil semua karyawan yang BELUM PUNYA DIVISI
        $unassignedEmployees = User::where('role', 'karyawan')
                                   ->whereNull('division_id')
                                   ->get();
        
        // 2. Kirim data divisi & karyawan yang tersedia ke view
        return view('admin.divisions.members', compact('division', 'unassignedEmployees'));
    }

    public function addMember(Request $request, Division $division)
    {
        // 1. Validasi input
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 2. Temukan user yang akan ditambahkan
        $userToAdd = User::find($request->user_id);

        // 3. Pastikan user itu adalah karyawan dan belum punya divisi
        if ($userToAdd && $userToAdd->role == 'karyawan' && is_null($userToAdd->division_id)) {
            // 4. Update division_id user tersebut
            $userToAdd->update([
                'division_id' => $division->id,
            ]);
            
            return redirect()->back()->with('success', 'Anggota berhasil ditambahkan.');
        }

        // 5. Jika gagal (misal: user tidak ditemukan atau sudah punya divisi)
        return redirect()->back()->with('error', 'Gagal menambahkan anggota. Pengguna tidak valid.');
    }

    public function removeMember(Division $division, User $user)
    {
        // 1. Jaring Pengaman: Pastikan user yang dikeluarkan memang anggota divisi ini
        if ($user->division_id !== $division->id) {
            return redirect()->back()->with('error', 'User bukan anggota dari divisi ini.');
        }

        // 2. Update division_id user menjadi NULL
        $user->update([
            'division_id' => null,
        ]);

        return redirect()->back()->with('success', 'Anggota berhasil dikeluarkan dari divisi.');
    }


}
