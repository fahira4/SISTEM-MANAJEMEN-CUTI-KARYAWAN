<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\User;

class DivisionController extends Controller
{
        public function index(Request $request)
    {
        $query = Division::with(['leader', 'members']);
        
        // Filter berdasarkan nama divisi
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Filter berdasarkan ketua divisi
        if ($request->has('leader_id') && $request->leader_id != '') {
            $query->where('leader_id', $request->leader_id);
        }
        
        // Filter berdasarkan jumlah anggota
        if ($request->has('member_count') && $request->member_count != '') {
            switch ($request->member_count) {
                case '0':
                    $query->has('members', '=', 0);
                    break;
                case '1-5':
                    $query->has('members', '>=', 1)->has('members', '<=', 5);
                    break;
                case '6-10':
                    $query->has('members', '>=', 6)->has('members', '<=', 10);
                    break;
                case '11+':
                    $query->has('members', '>=', 11);
                    break;
            }
        }
        
        $sortFields = $request->input('sort_fields', []);
        $sortDirections = $request->input('sort_directions', []);

        // ✅ PERBAIKAN: Ganti dengan withCount yang hanya menghitung karyawan (bukan ketua)
        $query->withCount(['members' => function($query) {
            $query->where('role', 'karyawan');
        }]);

        // Jika ada multiple sorting criteria
        if (!empty($sortFields)) {
            foreach ($sortFields as $index => $field) {
                if (isset($sortDirections[$index]) && in_array($sortDirections[$index], ['asc', 'desc'])) {
                    if ($field === 'members_count') {
                        // Sorting by member count - gunakan members_count yang sudah di-load
                        $query->orderBy('members_count', $sortDirections[$index]);
                    } else {
                        // Sorting langsung di field divisions
                        $query->orderBy($field, $sortDirections[$index]);
                    }
                }
            }
        } else {
            // Default sorting jika tidak ada sorting yang dipilih
            $query->orderBy('name', 'asc');
        }
        
        // PERBAIKAN: gunakan paginate() bukan get()
        $divisions = $query->paginate(10);
        $leaders = User::where('role', 'ketua_divisi')->get();
        
        return view('admin.divisions.index', compact('divisions', 'leaders'));
    }

    public function create()
    {
        // ✅ Hanya ambil ketua divisi yang BELUM memiliki divisi (belum memimpin divisi manapun)
        $availableLeaders = User::where('role', 'ketua_divisi')
                            ->whereNull('division_id') // Belum jadi anggota divisi manapun
                            ->where('active_status', true)
                            ->get();
        
        return view('admin.divisions.create', compact('availableLeaders'));
    }

    public function edit(Division $division)
    {
        // ✅ Untuk edit: tampilkan ketua divisi yang available + current leader
        $availableLeaders = User::where('role', 'ketua_divisi')
                            ->where(function($query) use ($division) {
                                $query->whereNull('division_id') // Belum punya divisi
                                        ->orWhere('id', $division->leader_id); // Atau current leader
                            })
                            ->where('active_status', true)
                            ->get();
        
        return view('admin.divisions.edit', compact('division', 'availableLeaders'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        // ✅ Validasi 1: Pastikan user adalah ketua divisi
        $leader = User::find($request->leader_id);
        if ($leader->role !== 'ketua_divisi') {
            return back()->withInput()->withErrors([
                'leader_id' => 'User yang dipilih bukan Ketua Divisi. Role: ' . $leader->role
            ]);
        }

        // ✅ Validasi 2: Pastikan ketua belum memimpin divisi lain
        if ($leader->leadingDivision) {
            return back()->withInput()->withErrors([
                'leader_id' => 'Ketua divisi ini sudah memimpin divisi: ' . $leader->leadingDivision->name
            ]);
        }

        // ✅ Buat divisi
        $division = Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        // ✅ Update division_id ketua divisi
        $leader->update(['division_id' => $division->id]);

        return redirect()->route('admin.divisions.index')
                        ->with('success', 'Divisi ' . $division->name . ' berhasil dibuat dengan ketua: ' . $leader->name);
    }

public function show(Division $division)
{
    // Jika request AJAX, return JSON untuk modal
    if (request()->ajax() || request()->wantsJson()) {
        $division->load(['leader', 'members' => function($query) {
            $query->select('id', 'name', 'email', 'active_status', 'division_id', 'join_date', 'role'); // TAMBAH 'role'
        }]);

        return response()->json([
            'id' => $division->id,
            'name' => $division->name,
            'description' => $division->description,
            'leader' => $division->leader ? [
                'id' => $division->leader->id,
                'name' => $division->leader->name,
                'email' => $division->leader->email,
            ] : null,
            'members_count' => $division->members->count(),
            'members' => $division->members->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'active_status' => $member->active_status,
                    'role' => $member->role, // TAMBAH ROLE
                    'join_date' => $member->join_date ? $member->join_date->format('d/m/Y') : null,
                ];
            }),
            'created_at' => $division->created_at->format('d/m/Y H:i'),
            'updated_at' => $division->updated_at->format('d/m/Y H:i'),
        ]);
    }

    // Untuk regular request, tampilkan halaman detail
    $division->load(['leader', 'members']);
    return view('admin.divisions.show', compact('division'));
}
    


    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
            'leader_id' => 'required|exists:users,id',
        ]);

        // ✅ Validasi 1: Pastikan user adalah ketua divisi
        $newLeader = User::find($request->leader_id);
        if ($newLeader->role !== 'ketua_divisi') {
            return back()->withInput()->withErrors([
                'leader_id' => 'User yang dipilih bukan Ketua Divisi. Role: ' . $newLeader->role
            ]);
        }

        // ✅ Validasi 2: Jika ganti ketua, pastikan ketua baru belum memimpin divisi lain
        if ($request->leader_id != $division->leader_id) {
            if ($newLeader->leadingDivision && $newLeader->leadingDivision->id != $division->id) {
                return back()->withInput()->withErrors([
                    'leader_id' => 'Ketua divisi ini sudah memimpin divisi lain: ' . $newLeader->leadingDivision->name
                ]);
            }

            // ✅ Handle perubahan ketua
            $oldLeader = $division->leader;
            if ($oldLeader) {
                // Hapus division_id dari ketua lama
                $oldLeader->update(['division_id' => null]);
            }
            
            // Set division_id untuk ketua baru
            $newLeader->update(['division_id' => $division->id]);
        }

        $division->update([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        return redirect()->route('admin.divisions.index')
                        ->with('success', 'Divisi ' . $division->name . ' berhasil diperbarui.');
    }

    public function destroy(Division $division)
    {
        // Validasi: Hanya admin yang bisa hapus
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.divisions.index')
                            ->with('error', 'Hanya Admin yang dapat menghapus divisi.');
        }

        // ✅ Reset ketua divisi
        if ($division->leader) {
            $division->leader->update(['division_id' => null]);
        }

        // Set division_id semua anggota menjadi null
        $division->members()->update(['division_id' => null]);

        $divisionName = $division->name;
        $division->delete();

        return redirect()->route('admin.divisions.index')
                        ->with('success', 'Divisi ' . $divisionName . ' berhasil dihapus. Semua anggota dan ketua telah dikeluarkan.');
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
