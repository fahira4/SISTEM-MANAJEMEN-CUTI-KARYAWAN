<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('division')->get();

        // 2. Kirim data tersebut ke file view
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();

        return view('admin.users.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', Rules\Password::defaults()],
        'role' => 'required|in:karyawan,ketua_divisi,hrd,admin',
        'division_id' => [
                            'nullable',
                            'prohibited_if:role,admin', // Larang jika role adalah admin
                            'prohibited_if:role,hrd',   // Larang jika role adalah hrd
                            'exists:divisions,id',      // Hanya jika divisinya ada 
                        ], // 'nullable' jika tidak dipilih, 'exists' memastikan divisinya ada
    ]);

    // 2. Buat user baru
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Enkripsi password
        'role' => $request->role,
        'division_id' => $request->division_id,
    ]);

    // 3. Alihkan kembali ke halaman index user dengan pesan sukses
    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna baru berhasil ditambahkan.');
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
    public function edit(User $user)
    {
        $divisions = Division::all();

    // Kirim user dan divisions ke view
        return view('admin.users.edit', compact('user', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,User $user)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Izinkan email unik jika itu email user sendiri
        'password' => ['nullable', Rules\Password::defaults()], // Password boleh kosong
        'role' => 'required|in:karyawan,ketua_divisi,hrd', // Peran tetap divalidasi
        'division_id' => [ // Aturan yang sama seperti 'store'
            'nullable',
            'prohibited_if:role,hrd',
            'exists:divisions,id',
        ],
    ]);

    // 2. Siapkan data untuk di-update
    $data = $request->only('name', 'email', 'role', 'division_id');

    // 3. Cek jika password diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // 4. Update data user
    $user->update($data);

    // 5. Alihkan kembali ke halaman index user
    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // 2. Jaring Pengaman: Cek peran (sesuai PDF)
    if ($user->role == 'admin' || $user->role == 'hrd') {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus Admin atau HRD.');
    }

    // 3. Jika aman, hapus user
    $user->delete();

    // 4. Alihkan kembali ke halaman index dengan pesan sukses
    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna berhasil dihapus.');
    }
}
