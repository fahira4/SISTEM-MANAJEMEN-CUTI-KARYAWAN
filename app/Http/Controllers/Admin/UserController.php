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
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua user, dengan relasi divisinya
        $users = User::with('division')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan formulir untuk membuat user baru.
     */
    public function create()
    {
        $divisions = Division::all();

        return view('admin.users.create', compact('divisions'));
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // Pesan Error Kustom
        $messages = [
            'division_id.prohibited_if' => 'Perhatian: Peran Admin atau HRD tidak diizinkan terikat pada Divisi Operasional. Mohon kosongkan Divisi untuk peran ini.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:karyawan,ketua_divisi,hrd', // Admin tidak termasuk di dropdown
            'division_id' => [
                'nullable',
                'prohibited_if:role,admin',
                'prohibited_if:role,hrd',
                'exists:divisions,id',
            ],
        ], $messages); // Masukkan $messages sebagai argumen kedua

        // Buat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role,
            'division_id' => $request->division_id,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail resource (tidak terpakai di sini).
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Menampilkan formulir untuk mengedit user.
     */
    public function edit(User $user)
    {
        $divisions = Division::all();

        return view('admin.users.edit', compact('user', 'divisions'));
    }

    /**
     * Memperbarui data user di storage.
     */
    public function update(Request $request, User $user)
    {
        // Pesan Error Kustom (Sama seperti store)
        $messages = [
            'division_id.prohibited_if' => 'Perhatian: Peran Admin atau HRD tidak diizinkan terikat pada Divisi Operasional. Mohon kosongkan Divisi untuk peran ini.',
        ];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Rules\Password::defaults()],
            'role' => 'required|in:karyawan,ketua_divisi,hrd',
            'division_id' => [
                'nullable',
                'prohibited_if:role,hrd',
                'exists:divisions,id',
            ],
        ], $messages); // Masukkan $messages sebagai argumen kedua

        // Siapkan data untuk di-update
        $data = $request->only('name', 'email', 'role', 'division_id');

        // Cek jika password diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update data user
        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus resource dari storage.
     */
    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Jaring Pengaman: Cek peran (sesuai PDF)
        if ($user->role == 'admin' || $user->role == 'hrd') {
            return redirect()->route('admin.users.index')
                             ->with('error', 'Anda tidak dapat menghapus Admin atau HRD.');
        }

        // Jika aman, hapus user
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}