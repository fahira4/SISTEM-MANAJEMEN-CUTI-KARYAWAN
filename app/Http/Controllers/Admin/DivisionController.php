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
        
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->has('leader_id') && $request->leader_id != '') {
            $query->where('leader_id', $request->leader_id);
        }
        
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

        $query->withCount(['members' => function($query) {
            $query->where('role', 'karyawan');
        }]);

        if (!empty($sortFields)) {
            foreach ($sortFields as $index => $field) {
                if (isset($sortDirections[$index]) && in_array($sortDirections[$index], ['asc', 'desc'])) {
                    if ($field === 'members_count') {
                        $query->orderBy('members_count', $sortDirections[$index]);
                    } else {
                        $query->orderBy($field, $sortDirections[$index]);
                    }
                }
            }
        } else {
            $query->orderBy('name', 'asc');
        }   
        $divisions = $query->paginate(10);
        $leaders = User::where('role', 'ketua_divisi')->get();
        
        return view('admin.divisions.index', compact('divisions', 'leaders'));
    }

    public function create()
    {   
        $existingLeaderIds = Division::pluck('leader_id')->all();
        $availableLeaders = User::where('role', 'ketua_divisi')
                            ->whereNotIn('id', $existingLeaderIds)
                            ->whereNull('division_id')
                            ->where('active_status', true)
                            ->get();
        
        return view('admin.divisions.create', compact('availableLeaders'));
    }

    public function edit(Division $division)
    {
        $availableLeaders = User::where('role', 'ketua_divisi')
                            ->where(function($query) use ($division) {
                                $query->whereNull('division_id') 
                                        ->orWhere('id', $division->leader_id); 
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

        $leader = User::find($request->leader_id);
        if ($leader->role !== 'ketua_divisi') {
            return back()->withInput()->withErrors([
                'leader_id' => 'User yang dipilih bukan Ketua Divisi. Role: ' . $leader->role
            ]);
        }
        if ($leader->leadingDivision) {
            return back()->withInput()->withErrors([
                'leader_id' => 'Ketua divisi ini sudah memimpin divisi: ' . $leader->leadingDivision->name
            ]);
        }

        $division = Division::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => $request->leader_id,
        ]);

        $leader->update(['division_id' => $division->id]);

        return redirect()->route('admin.divisions.index')
                        ->with('success', 'Divisi ' . $division->name . ' berhasil dibuat dengan ketua: ' . $leader->name);
    }

public function show(Division $division)
{
    if (request()->ajax() || request()->wantsJson()) {
        $division->load(['leader', 'members' => function($query) {
            $query->select('id', 'name', 'email', 'active_status', 'division_id', 'join_date', 'role'); 
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
                    'role' => $member->role,
                    'join_date' => $member->join_date ? $member->join_date->format('d/m/Y') : null,
                ];
            }),
            'created_at' => $division->created_at->format('d/m/Y H:i'),
            'updated_at' => $division->updated_at->format('d/m/Y H:i'),
        ]);
    }

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

        $newLeader = User::find($request->leader_id);
        if ($newLeader->role !== 'ketua_divisi') {
            return back()->withInput()->withErrors([
                'leader_id' => 'User yang dipilih bukan Ketua Divisi. Role: ' . $newLeader->role
            ]);
        }

        if ($request->leader_id != $division->leader_id) {
            if ($newLeader->leadingDivision && $newLeader->leadingDivision->id != $division->id) {
                return back()->withInput()->withErrors([
                    'leader_id' => 'Ketua divisi ini sudah memimpin divisi lain: ' . $newLeader->leadingDivision->name
                ]);
            }

            $oldLeader = $division->leader;
            if ($oldLeader) {
                $oldLeader->update(['division_id' => null]);
            }
            
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
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.divisions.index')
                            ->with('error', 'Hanya Admin yang dapat menghapus divisi.');
        }
        if ($division->leader) {
            $division->leader->update(['division_id' => null]);
        }

        $division->members()->update(['division_id' => null]);

        $divisionName = $division->name;
        $division->delete();

        return redirect()->route('admin.divisions.index')
                        ->with('success', 'Divisi ' . $divisionName . ' berhasil dihapus. Semua anggota dan ketua telah dikeluarkan.');
    }

    public function showMembers(Division $division)
    {
        $unassignedEmployees = User::where('role', 'karyawan')
                                   ->whereNull('division_id')
                                   ->get();
        
        return view('admin.divisions.members', compact('division', 'unassignedEmployees'));
    }

    public function addMember(Request $request, Division $division)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userToAdd = User::find($request->user_id);

        if ($userToAdd && $userToAdd->role == 'karyawan' && is_null($userToAdd->division_id)) {
            $userToAdd->update([
                'division_id' => $division->id,
            ]);
            
            return redirect()->back()->with('success', 'Anggota berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan anggota. Pengguna tidak valid.');
    }

    public function removeMember(Division $division, User $user)
    {
        if ($user->division_id !== $division->id) {
            return redirect()->back()->with('error', 'User bukan anggota dari divisi ini.');
        }

        $user->update([
            'division_id' => null,
        ]);

        return redirect()->back()->with('success', 'Anggota berhasil dikeluarkan dari divisi.');
    }


}
