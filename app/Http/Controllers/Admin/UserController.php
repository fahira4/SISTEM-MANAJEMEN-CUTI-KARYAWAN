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
    public function index(Request $request)
{
    $query = User::with('division');
    
    // Filter by role
    if ($request->has('role') && $request->role != '') {
        $query->where('role', $request->role);
    }
    
    // Filter by active status
    if ($request->has('active_status') && $request->active_status != '') {
        $query->where('active_status', $request->active_status);
    }
    
    // Filter by division
    if ($request->has('division_id') && $request->division_id != '') {
        $query->where('division_id', $request->division_id);
    }
    
    $users = $query->paginate(10);
    $divisions = Division::all(); // Untuk filter dropdown
    
    return view('admin.users.index', compact('users', 'divisions'));
}

  
    public function create()
    {
        $divisions = Division::all();

        return view('admin.users.create', compact('divisions'));
    }


public function store(Request $request)
{
        if ($request->role === 'admin') {
        // Cek apakah sudah ada admin
        $existingAdmin = User::where('role', 'admin')->first();
        if ($existingAdmin) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Hanya boleh ada satu Admin dalam sistem. Admin sudah ada: ' . $existingAdmin->name);
        }
    }

    $messages = [
        'division_id.prohibited_if' => 'Perhatian: Peran Admin atau HRD tidak diizinkan terikat pada Divisi Operasional. Mohon kosongkan Divisi untuk peran ini.',
    ];
    $request->validate([
        'username' => 'required|string|max:50|unique:users',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', Rules\Password::defaults()],
        'role' => 'required|in:karyawan,ketua_divisi,hrd', // HAPUS 'admin' dari sini
        'annual_leave_quota' => 'required|integer|min:0|max:365',
        'join_date' => 'required|date',
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'division_id' => [
            'nullable',
            'exists:divisions,id',
            function ($attribute, $value, $fail) use ($request) {

                if (in_array($request->role, ['admin', 'hrd']) && $value) {
                    $fail('Admin dan HRD tidak boleh memiliki divisi.');
                }
                if (in_array($request->role, ['karyawan', 'ketua_divisi']) && !$value) {
                    $fail('Karyawan dan Ketua Divisi wajib memiliki divisi.');
                }
            }
        ],
    ], $messages);

    User::create([
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'division_id' => $request->division_id,
        'annual_leave_quota' => $request->annual_leave_quota,
        'join_date' => $request->join_date,
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'active_status' => true,
    ]);

    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna baru berhasil ditambahkan.');
}

    
    public function show(string $id)
    {
        //
    }


   public function edit(User $user)
{
    // 🔒 PREVENT: Admin tidak boleh edit admin lain, dan tidak boleh edit diri sendiri via admin panel
    if ($user->role === 'admin') {
        if ($user->id === auth()->id()) {
            // Redirect admin ke profile page jika mencoba edit diri sendiri via admin panel
            return redirect()->route('profile.edit')
                             ->with('info', 'Untuk mengedit profil sendiri, silakan gunakan halaman Profile.');
        } else {
            // Block edit admin lain
            return redirect()->route('admin.users.index')
                             ->with('error', 'Tidak boleh mengedit user Admin lain.');
        }
    }

    $divisions = Division::all();
    return view('admin.users.edit', compact('user', 'divisions'));
}


public function update(Request $request, User $user)
{
    if ($request->role === 'admin' && $user->role !== 'admin') {
        $existingAdmin = User::where('role', 'admin')->first();
        if ($existingAdmin) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Hanya boleh ada satu Admin dalam sistem. Admin sudah ada: ' . $existingAdmin->name);
        }
    }

    // Security check: Prevent editing other admin users
    if ($user->role === 'admin' && $user->id !== auth()->id()) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Tidak boleh mengedit user Admin.');
    }


    $messages = [
        'division_id.prohibited_if' => 'Perhatian: Peran Admin atau HRD tidak diizinkan terikat pada Divisi Operasional. Mohon kosongkan Divisi untuk peran ini.',
    ];

    $request->validate([
        'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => ['nullable', Rules\Password::defaults()],
        'role' => 'required|in:karyawan,ketua_divisi,hrd', // HAPUS 'admin' dari sini
        'annual_leave_quota' => 'required|integer|min:0|max:365',
        'join_date' => 'required|date',
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'active_status' => 'required|boolean',
        'division_id' => [
            'nullable',
            'exists:divisions,id',
            function ($attribute, $value, $fail) use ($request, $user) {
                // Admin & HRD tidak boleh punya divisi
                if (in_array($request->role, ['admin', 'hrd']) && $value) {
                    $fail('Admin dan HRD tidak boleh memiliki divisi.');
                }
                
                // Karyawan dan Ketua Divisi wajib punya divisi
                if (in_array($request->role, ['karyawan', 'ketua_divisi']) && !$value) {
                    $fail('Karyawan dan Ketua Divisi wajib memiliki divisi.');
                }
                
                // Validasi khusus untuk Ketua Divisi
                if ($request->role === 'ketua_divisi' && $value) {
                    $division = Division::find($value);
                    if ($division && $division->leader_id && $division->leader_id != $user->id) {
                        $fail('Divisi ini sudah memiliki ketua divisi lain.');
                    }
                }
            }
        ],
    ], $messages);

    $data = [
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'division_id' => $request->division_id,
        'annual_leave_quota' => $request->annual_leave_quota,
        'join_date' => $request->join_date,
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'active_status' => $request->boolean('active_status'),
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna berhasil diperbarui.');
}

    public function destroy(User $user)
{
    // Prevent deleting admin user
    if ($user->role === 'admin') {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Tidak boleh menghapus user Admin.');
    }

    if ($user->id == auth()->id()) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // Jaring Pengaman: Cek peran (sesuai PDF)
    if ($user->role == 'admin' || $user->role == 'hrd') {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus Admin atau HRD.');
    }

    $user->delete();

    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna berhasil dihapus.');
}
}