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
    
    // Filter masa kerja
    if ($request->has('employment_period') && $request->employment_period != '') {
        $query->where(function($q) use ($request) {
            $now = now();
            switch ($request->employment_period) {
                case 'less_than_30_days':
                    $q->where('join_date', '>=', $now->subDays(30));
                    break;
                case '30_90_days':
                    $q->whereBetween('join_date', [$now->subDays(90), $now->subDays(30)]);
                    break;
                case '90_180_days':
                    $q->whereBetween('join_date', [$now->subDays(180), $now->subDays(90)]);
                    break;
                case '180_365_days':
                    $q->whereBetween('join_date', [$now->subDays(365), $now->subDays(180)]);
                    break;
                case 'more_than_1_year':
                    $q->where('join_date', '<=', $now->subDays(365));
                    break;
            }
        });
    }
    
    // MULTI-SORTING IMPLEMENTATION dengan NULL values di akhir
    $sortFields = $request->input('sort_fields', []);
    $sortDirections = $request->input('sort_directions', []);
    
    // Jika ada multiple sorting criteria
    if (!empty($sortFields)) {
        foreach ($sortFields as $index => $field) {
            if (isset($sortDirections[$index]) && in_array($sortDirections[$index], ['asc', 'desc'])) {
                if ($field === 'division') {
                    // Sorting by division name dengan NULL values di akhir
                    $query->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                          ->orderByRaw('CASE WHEN divisions.name IS NULL THEN 1 ELSE 0 END') // NULL di akhir
                          ->orderBy('divisions.name', $sortDirections[$index])
                          ->select('users.*');
                } else {
                    // Sorting langsung di field users
                    $query->orderBy($field, $sortDirections[$index]);
                }
            }
        }
    } else {
        // Default sorting jika tidak ada sorting yang dipilih
        $query->orderBy('name', 'asc');
    }
    
    $users = $query->paginate(10);
    $divisions = Division::all();
    
    return view('admin.users.index', compact('users', 'divisions'));
}
  
    public function create()
    {

        return view('admin.users.create');
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

    // VALIDASI SESUAI REQUIREMENTS
    $request->validate([
        'username' => 'required|string|max:50|unique:users',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', Rules\Password::defaults()],
        'role' => 'required|in:karyawan,ketua_divisi,hrd,admin',
        'annual_leave_quota' => 'required|integer|min:0|max:365',
    ]);

    User::create([
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'annual_leave_quota' => $request->annual_leave_quota,
        'division_id' => null, // SELALU NULL karena divisi dihapus
        'join_date' => now(), // Otomatis tanggal sekarang
        'active_status' => true, // Otomatis aktif
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
    // 🔒 PREVENT: Admin tidak boleh edit admin lain
    if ($user->role === 'admin' && $user->id !== auth()->id()) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Tidak boleh mengedit user Admin lain.');
    }

    // HILANGKAN divisions karena tidak diperlukan
    return view('admin.users.edit', compact('user'));
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

    // VALIDASI SESUAI REQUIREMENTS
    $request->validate([
        'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => ['nullable', Rules\Password::defaults()],
        'role' => 'required|in:karyawan,ketua_divisi,hrd,admin',
        'active_status' => 'required|boolean',
    ]);

    $data = [
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'division_id' => null, // SELALU NULL karena divisi dihapus
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

    // TAMBAHKAN: Prevent deleting HRD user
    if ($user->role === 'hrd') {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Tidak boleh menghapus user HRD.');
    }

    if ($user->id == auth()->id()) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // Jaring Pengaman: Cek peran (sesuai PDF) - HAPUS HRD DARI SINI
    if ($user->role == 'admin') { // HAPUS 'hrd' dari sini
        return redirect()->route('admin.users.index')
                         ->with('error', 'Anda tidak dapat menghapus Admin.');
    }

    $user->delete();

    return redirect()->route('admin.users.index')
                     ->with('success', 'Pengguna berhasil dihapus.');
}

    /**
 * Apply multiple sorting to query
 */
private function applyMultiSort($query, $sortFields, $sortDirections)
{
    foreach ($sortFields as $index => $field) {
        if (isset($sortDirections[$index]) && in_array($sortDirections[$index], ['asc', 'desc'])) {
            if ($field === 'division') {
                $query->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                      ->orderBy('divisions.name', $sortDirections[$index])
                      ->select('users.*');
            } else {
                $query->orderBy($field, $sortDirections[$index]);
            }
        }
    }
    return $query;
}

}