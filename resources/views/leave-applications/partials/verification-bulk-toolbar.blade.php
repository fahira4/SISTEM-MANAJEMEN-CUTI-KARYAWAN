@if(!$pendingApplications->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        {{-- Selection Info --}}
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="selectAll" class="ml-2 text-sm text-gray-700">
                    Pilih Semua
                </label>
            </div>
            <div id="selectedCount" class="text-sm text-gray-600 hidden">
                <span id="count">0</span> dipilih
            </div>
        </div>

        {{-- Bulk Actions --}}
        <div class="flex items-center space-x-3" id="bulkActions" style="display: none;">
            <button onclick="openBulkRejectModal()" 
                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-colors duration-200">
                Tolak yang Dipilih
            </button>
            <button onclick="openBulkApproveModal()" 
                    class="px-4 py-2 bg-green-600 rounded-lg text-sm font-semibold text-white hover:bg-green-700 transition-colors duration-200 shadow-sm">
                Setujui yang Dipilih
            </button>
        </div>
    </div>
</div>
@endif