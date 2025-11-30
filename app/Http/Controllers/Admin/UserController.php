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
    public function index(Request $request)
{
    $query = User::with(['division', 'leadingDivision']);
    
    if ($request->has('role') && $request->role != '') {
        $query->where('role', $request->role);
    }
    if ($request->has('active_status') && $request->active_status != '') {
        $query->where('active_status', $request->active_status);
    }
    if ($request->has('division_id') && $request->division_id != '') {
        $query->where('division_id', $request->division_id);
    }
    
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

    $sortFields = $request->input('sort_fields', []);
    $sortDirections = $request->input('sort_directions', []);
    
    if (!empty($sortFields)) {
        foreach ($sortFields as $index => $field) {
            if (isset($sortDirections[$index]) && in_array($sortDirections[$index], ['asc', 'desc'])) {
                if ($field === 'division') {
                    $query->leftJoin('divisions', 'users.division_id', '=', 'divisions.id')
                          ->orderByRaw('CASE WHEN divisions.name IS NULL THEN 1 ELSE 0 END') 
                          ->orderBy('divisions.name', $sortDirections[$index])
                          ->select('users.*');
                } else {
                    $query->orderBy($field, $sortDirections[$index]);
                }
            }
        }
    } else {
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
            $existingAdmin = User::where('role', 'admin')->first();
            if ($existingAdmin) {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Hanya boleh ada satu Admin dalam sistem. Admin sudah ada: ' . $existingAdmin->name);
            }
        }

        if ($request->role === 'hrd') {
            $existingHrd = User::where('role', 'hrd')->first();
            if ($existingHrd) {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Hanya boleh ada satu HRD dalam sistem. HRD sudah ada: ' . $existingHrd->name);
            }
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', Rules\Password::defaults()],
            'role' => 'required|in:karyawan,ketua_divisi,hrd,admin',
            'annual_leave_quota' => 'required|integer|min:0|max:365',
            'join_date' => 'required|date',
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'annual_leave_quota' => 12,
            'division_id' => null,
            'join_date' => $request->join_date,
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
        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak boleh mengedit user Admin lain.');
        }

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

        if ($request->role === 'hrd' && $user->role !== 'hrd') {
            $existingHrd = User::where('role', 'hrd')->first();
            if ($existingHrd) {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Hanya boleh ada satu HRD dalam sistem. HRD sudah ada: ' . $existingHrd->name);
            }
        }

        if ($user->role === 'admin' && $user->id !== auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak boleh mengedit user Admin.');
        }

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:karyawan,ketua_divisi,hrd,admin',
            'active_status' => 'required|boolean',
            'join_date' => 'required|date',
        ]);

        $user->update([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'division_id' => null,
            'join_date' => $request->join_date,
            'active_status' => $request->boolean('active_status'),
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak boleh menghapus user Admin.');
        }

        if ($user->role === 'hrd') {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Tidak boleh menghapus user HRD.');
        }

        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role == 'admin') { 
            return redirect()->route('admin.users.index')
                            ->with('error', 'Anda tidak dapat menghapus Admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Pengguna berhasil dihapus.');
    }

    
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