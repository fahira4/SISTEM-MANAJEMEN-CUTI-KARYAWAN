{{-- MULTIPLE ACTION TOOLBAR --}}
@if($pendingApplications->count() > 0)
<div id="bulk-toolbar" class="mt-6 pt-6 border-t border-gray-100 hidden">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            {{-- Checkbox di sisi kiri --}}
            <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                <label for="select-all" class="text-sm font-medium text-gray-700 whitespace-nowrap">Pilih Semua</label>
            </div>
            
            <span id="selected-count" class="text-xs bg-blue-100 text-blue-800 px-3 py-1.5 rounded-full font-medium border border-blue-200">
                <span id="count">0</span> terpilih
            </span>
        </div>
        
        <div class="flex gap-2">
            <button type="button" 
                    id="bulk-approve-btn"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition duration-200 whitespace-nowrap flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border-2 border-green-700"
                    disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @if(Auth::user()->role == 'hrd')
                    Approve Selected
                @else
                    Setujui Selected
                @endif
            </button>
            <button type="button" 
                    id="bulk-reject-btn"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition duration-200 whitespace-nowrap flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border-2 border-red-700"
                    disabled>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                @if(Auth::user()->role == 'hrd')
                    Reject Selected
                @else
                    Tolak Selected
                @endif
            </button>
        </div>
    </div>
</div>
@endif