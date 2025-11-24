<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Divisi: ') }} {{ $division->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Breadcrumb -->
                    <div class="mb-6 flex items-center text-sm text-gray-500">
                        <a href="{{ route('admin.divisions.index') }}" class="hover:text-gray-700">Divisi</a>
                        <span class="mx-2">/</span>
                        <span class="text-gray-900 font-medium">{{ $division->name }}</span>
                    </div>

                    <!-- Informasi Divisi -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Informasi Utama -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Divisi</h3>
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Nama Divisi:</dt>
                                    <dd class="text-sm text-gray-900">{{ $division->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Ketua Divisi:</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($division->leader)
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $division->leader->name }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Anggota:</dt>
                                    <dd class="text-sm text-gray-900">
                                        <span class="px-2 py-1 rounded-full text-sm font-medium  text-gray-800">
                                            {{ $division->members->count() }} anggota
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Dibuat:</dt>
                                    <dd class="text-sm text-gray-900">{{ $division->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Diupdate:</dt>
                                    <dd class="text-sm text-gray-900">{{ $division->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Deskripsi -->
                        <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi Divisi</h3>
                            <div class="prose prose-sm max-w-none">
                                @if($division->description)
                                    <p class="text-gray-700">{{ $division->description }}</p>
                                @else
                                    <p class="text-gray-400 italic">Tidak ada deskripsi</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Anggota -->
                    <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Daftar Anggota ({{ $division->members->count() }})</h3>
                            <a href="{{ route('admin.divisions.members.show', $division->id) }}" 
                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 transition duration-150">
                                Kelola Anggota
                            </a>
                        </div>

                        @if($division->members->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peran</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($division->members as $member)
                                            <tr class="hover:bg-gray-50 transition duration-150">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $member->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                                        ($member->role === 'hrd' ? 'bg-purple-100 text-purple-800' : 
                                                        ($member->role === 'ketua_divisi' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $member->role)) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $member->active_status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $member->active_status ? 'Aktif' : 'Non-Aktif' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $member->join_date ? \Carbon\Carbon::parse($member->join_date)->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada anggota</h3>
                                <p class="mt-1 text-sm text-gray-500">Tambahkan anggota pertama ke divisi ini.</p>
                            </div>
                        @endif
                    </div>

                                        <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.divisions.index') }}" 
                        class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            ← Kembali ke Daftar
                        </a>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.divisions.edit', $division->id) }}" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                Edit Divisi
                            </a>
                            
                            @if(auth()->user()->role === 'admin')
                                <form action="{{ route('admin.divisions.destroy', $division->id) }}" 
                                    method="POST" 
                                    onsubmit="return confirmDoubleDelete('{{ addslashes($division->name) }}', {{ $division->members->count() }})">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out font-medium">
                                        Hapus Divisi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script>
// DOUBLE CONFIRMATION DELETE untuk halaman detail - FIXED VERSION
function confirmDoubleDelete(divisionName, memberCount) {
    // First confirmation - General warning
    const firstConfirm = confirm(`HAPUS DIVISI: "${divisionName}"\n\n• Divisi akan dihapus permanen\n• ${memberCount} anggota akan dikeluarkan\n• Tindakan ini mempengaruhi data cuti\n\nLanjutkan penghapusan?`);
    
    if (!firstConfirm) {
        console.log('Penghapusan dibatalkan pada konfirmasi pertama');
        return false; // PASTIKAN return false
    }
    
    // Second confirmation - Final warning with stronger message
    const secondConfirm = confirm(`⚠️ KONFIRMASI AKHIR - TIDAK DAPAT DIBATALKAN!\n\nAnda akan menghapus divisi "${divisionName}" secara permanen.\n\nAKIBAT:\n• ${memberCount} anggota kehilangan divisi\n• Data historis tetap tersimpan\n• Divisi tidak dapat dikembalikan\n\nTekan OK untuk menghapus, atau Cancel untuk membatalkan.`);
    
    if (!secondConfirm) {
        console.log('Penghapusan dibatalkan pada konfirmasi kedua');
        return false; // PASTIKAN return false
    }
    
    console.log('Penghapusan dikonfirmasi, melanjutkan...');
    return true; // Hanya return true jika kedua konfirmasi disetujui
}

// Loading state untuk tombol hapus
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirmDoubleDelete"]');
    
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                // Tambahkan loading state
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '🔄 Menghapus...';
                
                // Safety timeout - enable button setelah 10 detik
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                }, 10000);
            }
        });
    });
});
</script>
</x-app-layout>