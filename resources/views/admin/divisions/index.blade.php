<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Divisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Semua Divisi</h3>
                        
                        <a href="{{ route('admin.divisions.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                           style="background-color: #1f2937;"> 
                            + Tambah Divisi Baru
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.divisions.index') }}" id="filterForm">
                        <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                            
                            <div class="bg-white p-5 border border-gray-200 rounded-lg shadow-sm flex flex-col">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    Filter Data Divisi
                                </h4>
                                <div class="grid grid-cols-1 gap-4 flex-1">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Divisi</label>
                                        <input type="text" 
                                               name="name" 
                                               value="{{ request('name') }}"
                                               placeholder="Cari nama divisi..."
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Ketua Divisi</label>
                                        <select name="leader_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Semua Ketua</option>
                                            @foreach($leaders as $leader)
                                                <option value="{{ $leader->id }}" {{ request('leader_id') == $leader->id ? 'selected' : '' }}>
                                                    {{ $leader->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anggota</label>
                                        <select name="member_count" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Semua Jumlah</option>
                                            <option value="0" {{ request('member_count') === '0' ? 'selected' : '' }}>0 anggota</option>
                                            <option value="1-5" {{ request('member_count') === '1-5' ? 'selected' : '' }}>1-5 anggota</option>
                                            <option value="6-10" {{ request('member_count') === '6-10' ? 'selected' : '' }}>6-10 anggota</option>
                                            <option value="11+" {{ request('member_count') === '11+' ? 'selected' : '' }}>11+ anggota</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-5 border border-gray-200 rounded-lg shadow-sm flex flex-col">
                                <h4 class="font-medium text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                                    </svg>
                                    Multi Sorting
                                </h4>
                                
                                <div class="space-y-4 flex-1">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="name" 
                                                       {{ in_array('name', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Nama Divisi</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500" 
                                                    {{ !in_array('name', request('sort_fields', [])) ? 'disabled' : '' }}>
                                                <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>A → Z</option>
                                                <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'name') ? 'selected' : '' }}>Z → A</option>
                                            </select>
                                        </div>

                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="members_count" 
                                                       {{ in_array('members_count', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Jumlah Anggota</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                                    {{ !in_array('members_count', request('sort_fields', [])) ? 'disabled' : '' }}>
                                                <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'members_count') ? 'selected' : '' }}>Sedikit → Banyak</option>
                                                <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'members_count') ? 'selected' : '' }}>Banyak → Sedikit</option>
                                            </select>
                                        </div>

                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-150">
                                            <label class="flex items-center space-x-3 cursor-pointer flex-1">
                                                <input type="checkbox" name="sort_fields[]" value="created_at" 
                                                       {{ in_array('created_at', request('sort_fields', [])) ? 'checked' : '' }}
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Tanggal Dibuat</span>
                                            </label>
                                            <select name="sort_directions[]" class="text-sm border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500"
                                                    {{ !in_array('created_at', request('sort_fields', [])) ? 'disabled' : '' }}>
                                                <option value="asc" {{ (request('sort_directions.0') == 'asc' && request('sort_fields.0') == 'created_at') ? 'selected' : '' }}>Terlama → Terbaru</option>
                                                <option value="desc" {{ (request('sort_directions.0') == 'desc' && request('sort_fields.0') == 'created_at') ? 'selected' : '' }}>Terbaru → Terlama</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-auto">
                                        <div class="flex items-start">
                                            <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-xs text-blue-700">Pilih satu atau lebih field untuk sorting. Data akan diurutkan berdasarkan prioritas dari atas ke bawah.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-4 mt-4 border-t border-gray-200">
                                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                        Terapkan Filter & Sorting
                                    </button>
                                    <a href="{{ route('admin.divisions.index') }}" class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium text-center">
                                        Reset All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Divisi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ketua Divisi
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Anggota
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Aksi</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($divisions as $division)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $division->name }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $division->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($division->leader)
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ substr($division->leader->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $division->leader->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $division->leader->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium hover:bg-blue-200 transition duration-150">
                                                {{ $division->members_count }} anggota
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $division->created_at->format('d/m/Y') }}
                                            <div class="text-xs text-gray-400">{{ $division->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 max-w-xs truncate" title="{{ $division->description }}">
                                                {{ $division->description ?? 'Tidak ada deskripsi' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.divisions.show', $division->id) }}" 
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition duration-150"
                                                    title="Detail Divisi">
                                                    Detail
                                                </a>
                                                
                                                <a href="{{ route('admin.divisions.edit', $division->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                                   title="Edit Divisi">
                                                    Edit
                                                </a>
                                                
                                                <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                                                   class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50"
                                                   title="Kelola Anggota">
                                                    Kelola anggota
                                                </a>
                                                                                            
                                                @if(auth()->user()->role === 'admin')
                                                    <form action="{{ route('admin.divisions.destroy', $division->id) }}" 
                                                        method="POST" 
                                                        class="inline-block"
                                                        onsubmit="return confirmDoubleDelete('{{ addslashes($division->name) }}', {{ $division->members->count() }})">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition duration-150"
                                                                title="Hapus Divisi">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center">
                                            <div class="text-center text-gray-500">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada divisi</h3>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    @if(request()->anyFilled(['name', 'leader_id', 'member_count']))
                                                        Coba ubah filter pencarian Anda.
                                                    @else
                                                        Mulai dengan membuat divisi pertama Anda.
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($divisions->hasPages())
                        <div class="mt-6">
                            {{ $divisions->withQueryString()->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div id="divisionDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Detail Divisi</h3>
                    <button onclick="closeDivisionDetail()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mt-4" id="modalContent">
                </div>
                
                <div class="flex justify-end pt-4 border-t mt-4">
                    <button onclick="closeDivisionDetail()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-150">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
// Double confirmation delete function
function confirmDoubleDelete(divisionName, memberCount) {
    console.log('confirmDoubleDelete called for:', divisionName, 'members:', memberCount);
    
    // First confirmation
    const firstConfirm = confirm(`HAPUS DIVISI: "${divisionName}"\n\n• ${memberCount} anggota akan dikeluarkan\n• Data divisi akan hilang permanen\n\nLanjutkan penghapusan?`);
    
    if (!firstConfirm) {
        console.log('User cancelled at first confirmation');
        return false;
    }
    
    // Second confirmation
    const secondConfirm = confirm(`KONFIRMASI AKHIR!\n\nYakin hapus divisi "${divisionName}" secara PERMANEN?\n\n• ${memberCount} anggota akan kehilangan divisi\n• Tindakan ini TIDAK DAPAT DIBATALKAN\n\nTekan OK untuk hapus, Cancel untuk batal.`);
    
    console.log('Second confirmation result:', secondConfirm);
    return secondConfirm;
}

// Show division detail modal
function showDivisionDetail(divisionId) {
    fetch(`/admin/divisions/${divisionId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('modalTitle').textContent = `Detail Divisi: ${data.name}`;
        
        // FIXED: Use backticks for template literals
        document.getElementById('modalContent').innerHTML = 
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-700">Informasi Divisi</h4>
                    <dl class="mt-2 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Nama Divisi:</dt>
                            <dd class="font-medium">${data.name}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Ketua Divisi:</dt>
                            <dd class="font-medium">${data.leader ? data.leader.name : '-'}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Jumlah Anggota:</dt>
                            <dd class="font-medium">${data.members_count} orang</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Dibuat:</dt>
                            <dd class="font-medium">${data.created_at}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700">Deskripsi</h4>
                    <p class="mt-2 text-sm text-gray-600">${data.description || 'Tidak ada deskripsi'}</p>
                </div>
            </div>
            
            ${data.members_count > 0 ? 
            <div class="mt-6">
                <h4 class="font-medium text-gray-700 mb-3">Daftar Anggota (${data.members_count})</h4>
                <div class="max-h-60 overflow-y-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nama</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Email</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Peran</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${data.members.map(member => {
                                const roleColor = member.role === 'admin' ? 'bg-red-100 text-red-800' : 
                                                member.role === 'hrd' ? 'bg-purple-100 text-purple-800' : 
                                                member.role === 'ketua_divisi' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                const roleText = member.role.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                                
                                return 
                                <tr>
                                    <td class="px-4 py-2 text-sm">${member.name}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">${member.email}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full ${roleColor}">
                                            ${roleText}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 text-xs rounded-full ${member.active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${member.active_status ? 'Aktif' : 'Non-Aktif'}
                                        </span>
                                    </td>
                                </tr>
                                ;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
            : 
            <div class="mt-6 text-center py-4 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Tidak ada anggota dalam divisi ini</p>
            </div>
            }
        ;
        
        document.getElementById('divisionDetailModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memuat detail divisi. Silakan refresh halaman dan coba lagi.');
    });
}

// Close modal function
function closeDivisionDetail() {
    document.getElementById('divisionDetailModal').classList.add('hidden');
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Division Management JavaScript loaded');
    
    // Initialize sorting checkboxes
    const sortCheckboxes = document.querySelectorAll('input[name="sort_fields[]"]');
    
    // Initialize disabled state on page load
    sortCheckboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            const parentDiv = checkbox.closest('.flex.items-center.justify-between');
            const select = parentDiv.querySelector('select');
            if (select) {
                select.disabled = true;
            }
        }
    });

    // Enable/disable select based on checkbox state
    sortCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const parentDiv = this.closest('.flex.items-center.justify-between');
            const select = parentDiv.querySelector('select');
            if (select) {
                select.disabled = !this.checked;
                
                if (!this.checked) {
                    select.value = 'asc';
                }
            }
        });
    });

    // Close modal when clicking outside
    document.getElementById('divisionDetailModal').addEventListener('click', function(e) {
        if (e.target.id === 'divisionDetailModal') {
            closeDivisionDetail();
        }
    });

    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDivisionDetail();
        }
    });

    // PERBAIKAN: Loading state hanya setelah konfirmasi berhasil
document.querySelectorAll('form[onsubmit*="confirmDoubleDelete"]').forEach(form => {
    // Simpan original onsubmit
    const originalOnsubmit = form.onsubmit;
    
    // Ganti dengan handler baru
    form.onsubmit = function(e) {
        // Ekstrak parameter dari onsubmit string
        const onsubmitString = this.getAttribute('onsubmit');
        const divisionNameMatch = onsubmitString.match(/return confirmDoubleDelete\('([^']+)', (\d+)\)/);
        
        if (divisionNameMatch) {
            const divisionName = divisionNameMatch[1];
            const memberCount = parseInt(divisionNameMatch[2]);
            
            // Jalankan konfirmasi dulu
            const confirmed = confirmDoubleDelete(divisionName, memberCount);
            
            if (!confirmed) {
                e.preventDefault(); // Stop form submission
                return false;
            }
            
            // HANYA JIKA KONFIRMASI BERHASIL, tampilkan loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '🔄 Menghapus...';
                
                // Safety timeout
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = 'Hapus';
                    }
                }, 3000);
            }
        }
        
        return true; // Lanjutkan form submission
    };
});
   
});
</script>
</x-app-layout>