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
       $leaders = User::where('role', 'ketua_divisi')->get();

        return view('admin.divisions.create', compact('leaders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255|unique:divisions',
        'description' => 'nullable|string',
        'leader_id' => 'nullable|exists:users,id',
    ]);

    // 2. Buat divisi baru menggunakan data yang sudah divalidasi
        Division::create([
        'name' => $request->name,
        'description' => $request->description,
        'leader_id' => $request->leader_id,
    ]);

    // 3. Alihkan kembali ke halaman index (daftar divisi) dengan pesan sukses
    return redirect()->route('admin.divisions.index')
                     ->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        $leaders = User::where('role', 'ketua_divisi')->get();

        return view('admin.divisions.edit', compact('division', 'leaders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
        'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
        'description' => 'nullable|string',
        'leader_id' => 'nullable|exists:users,id',
    ]);

    // 2. Update data divisi
    $division->update([
        'name' => $request->name,
        'description' => $request->description,
        'leader_id' => $request->leader_id,
    ]);

    // 3. Alihkan kembali ke halaman index dengan pesan sukses
    return redirect()->route('admin.divisions.index')
                     ->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        $division->delete();

    // 2. Alihkan kembali ke halaman index dengan pesan sukses
    return redirect()->route('admin.divisions.index')
                     ->with('success', 'Divisi berhasil dihapus.');
    }

    public function showMembers(Division $division)
    {
    // 1. Ambil semua karyawan yang BELUM PUNYA DIVISI
    // Sesuai PDF: "memilih karyawan ... yang belum memiliki divisi" [cite: 923]
    $unassignedEmployees = User::where('role', 'karyawan')
                               ->whereNull('division_id')
                               ->get();
    
    // 2. Kirim data divisi & karyawan yang tersedia ke view
    return view('admin.divisions.members', compact('division', 'unassignedEmployees'));
    }

/**
 * Menambahkan seorang anggota (karyawan) ke dalam divisi.
 */
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
