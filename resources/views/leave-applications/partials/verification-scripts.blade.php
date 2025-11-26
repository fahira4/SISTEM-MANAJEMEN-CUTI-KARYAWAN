<script>
    // Single Approval/Reject Functions
    function openApproveModal(id, name) {
        let url = "{{ route('leave-applications.approve', ':id') }}".replace(':id', id);
        document.getElementById('approveForm').action = url;
        document.getElementById('approveNamePlaceholder').textContent = name;
        document.getElementById('approveModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openRejectModal(id, name) {
        let url = "{{ route('leave-applications.reject', ':id') }}".replace(':id', id);
        document.getElementById('rejectForm').action = url;
        document.getElementById('rejectNamePlaceholder').textContent = name;
        document.getElementById('rejectModal').classList.remove('hidden');
        
        // Reset form state ketika modal dibuka
        setTimeout(() => {
            const textarea = document.getElementById('rejection_notes');
            const errorElement = document.getElementById('rejectionNotesError');
            const counterElement = document.getElementById('currentChars');
            const submitBtn = document.getElementById('rejectSubmitBtn');
            
            textarea.value = '';
            errorElement.classList.add('hidden');
            counterElement.textContent = '0';
            textarea.classList.remove('border-red-300', 'bg-red-50');
            textarea.classList.add('border-gray-300');
            submitBtn.disabled = true;
            textarea.focus();
        }, 100);
        
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Single Validation Functions
    function validateRejectionNotesLive(textarea) {
        const text = textarea.value;
        const errorElement = document.getElementById('rejectionNotesError');
        const counterElement = document.getElementById('currentChars');
        const submitBtn = document.getElementById('rejectSubmitBtn');
        
        // Update character counter
        counterElement.textContent = text.length;
        
        // Validate minimum length
        if (text.length < 10 && text.length > 0) {
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.classList.remove('border-gray-300');
            submitBtn.disabled = true;
        } else {
            errorElement.classList.add('hidden');
            textarea.classList.remove('border-red-300', 'bg-red-50');
            textarea.classList.add('border-gray-300');
            submitBtn.disabled = text.length === 0;
        }
        
        // Validate maximum length
        if (text.length > 500) {
            errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
            errorElement.classList.remove('hidden');
            submitBtn.disabled = true;
        } else if (text.length < 10) {
            errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
        }
    }

    function validateRejectionNotes() {
        const textarea = document.getElementById('rejection_notes');
        const text = textarea.value.trim();
        
        if (text.length < 10) {
            // Show error and focus
            const errorElement = document.getElementById('rejectionNotesError');
            errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.focus();
            return false;
        }
        
        if (text.length > 500) {
            const errorElement = document.getElementById('rejectionNotesError');
            errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.focus();
            return false;
        }
        
        return true;
    }

    // Bulk Selection Functions
    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.leave-checkbox:checked');
        const selectedCount = checkboxes.length;
        const selectedCountElement = document.getElementById('selectedCount');
        const countElement = document.getElementById('count');
        const bulkActions = document.getElementById('bulkActions');
        const selectAll = document.getElementById('selectAll');

        countElement.textContent = selectedCount;
        
        if (selectedCount > 0) {
            selectedCountElement.classList.remove('hidden');
            bulkActions.style.display = 'flex';
        } else {
            selectedCountElement.classList.add('hidden');
            bulkActions.style.display = 'none';
        }

        // Update select all checkbox state
        const totalCheckboxes = document.querySelectorAll('.leave-checkbox').length;
        selectAll.checked = selectedCount === totalCheckboxes && totalCheckboxes > 0;
        selectAll.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
    }

    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.leave-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        
        updateBulkActions();
    }

    // Bulk Modal Functions
    function openBulkApproveModal() {
        const checkboxes = document.querySelectorAll('.leave-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Pilih setidaknya satu pengajuan untuk disetujui.');
            return;
        }

        document.getElementById('bulkApproveCount').textContent = selectedIds.length;
        
        // Clear and repopulate hidden fields
        const bulkApproveIds = document.getElementById('bulkApproveIds');
        bulkApproveIds.innerHTML = '';
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'leave_ids[]';
            input.value = id;
            bulkApproveIds.appendChild(input);
        });

        document.getElementById('bulkApproveModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBulkApproveModal() {
        document.getElementById('bulkApproveModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openBulkRejectModal() {
        const checkboxes = document.querySelectorAll('.leave-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            alert('Pilih setidaknya satu pengajuan untuk ditolak.');
            return;
        }

        document.getElementById('bulkRejectCount').textContent = selectedIds.length;
        
        // Clear and repopulate hidden fields
        const bulkRejectIds = document.getElementById('bulkRejectIds');
        bulkRejectIds.innerHTML = '';
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'leave_ids[]';
            input.value = id;
            bulkRejectIds.appendChild(input);
        });

        // Reset form state
        setTimeout(() => {
            const textarea = document.getElementById('bulk_rejection_notes');
            const errorElement = document.getElementById('bulkRejectionNotesError');
            const counterElement = document.getElementById('bulkCurrentChars');
            const submitBtn = document.getElementById('bulkRejectSubmitBtn');
            
            textarea.value = '';
            errorElement.classList.add('hidden');
            counterElement.textContent = '0';
            textarea.classList.remove('border-red-300', 'bg-red-50');
            textarea.classList.add('border-gray-300');
            submitBtn.disabled = true;
            textarea.focus();
        }, 100);

        document.getElementById('bulkRejectModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBulkRejectModal() {
        document.getElementById('bulkRejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Bulk Validation Functions
    function validateBulkRejectionNotesLive(textarea) {
        const text = textarea.value;
        const errorElement = document.getElementById('bulkRejectionNotesError');
        const counterElement = document.getElementById('bulkCurrentChars');
        const submitBtn = document.getElementById('bulkRejectSubmitBtn');
        
        counterElement.textContent = text.length;
        
        if (text.length < 10 && text.length > 0) {
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.classList.remove('border-gray-300');
            submitBtn.disabled = true;
        } else {
            errorElement.classList.add('hidden');
            textarea.classList.remove('border-red-300', 'bg-red-50');
            textarea.classList.add('border-gray-300');
            submitBtn.disabled = text.length === 0;
        }
        
        if (text.length > 500) {
            errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
            errorElement.classList.remove('hidden');
            submitBtn.disabled = true;
        } else if (text.length < 10) {
            errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
        }
    }

    function validateBulkRejectionNotes() {
        const textarea = document.getElementById('bulk_rejection_notes');
        const text = textarea.value.trim();
        
        if (text.length < 10) {
            const errorElement = document.getElementById('bulkRejectionNotesError');
            errorElement.textContent = 'Alasan penolakan harus minimal 10 karakter';
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.focus();
            return false;
        }
        
        if (text.length > 500) {
            const errorElement = document.getElementById('bulkRejectionNotesError');
            errorElement.textContent = 'Alasan penolakan maksimal 500 karakter';
            errorElement.classList.remove('hidden');
            textarea.classList.add('border-red-300', 'bg-red-50');
            textarea.focus();
            return false;
        }
        
        return true;
    }

    // Event Listeners
    document.getElementById('selectAll').addEventListener('change', toggleSelectAll);

    // Close modal dengan ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === "Escape") {
            closeApproveModal();
            closeRejectModal();
            closeBulkApproveModal();
            closeBulkRejectModal();
        }
    });

    // Close modal ketika klik di luar modal content
    document.addEventListener('click', (e) => {
        if (e.target.id === 'approveModal') {
            closeApproveModal();
        }
        if (e.target.id === 'rejectModal') {
            closeRejectModal();
        }
        if (e.target.id === 'bulkApproveModal') {
            closeBulkApproveModal();
        }
        if (e.target.id === 'bulkRejectModal') {
            closeBulkRejectModal();
        }
    });

    // Initialize bulk actions on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateBulkActions();
    });

    // Filter Enhancement Functions
function clearFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// Auto-submit form on some filters
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit date filters when both are filled
    const dateFrom = document.querySelector('input[name="date_from"]');
    const dateTo = document.querySelector('input[name="date_to"]');
    
    if (dateFrom && dateTo) {
        [dateFrom, dateTo].forEach(input => {
            input.addEventListener('change', function() {
                if (dateFrom.value && dateTo.value) {
                    dateFrom.closest('form').submit();
                }
            });
        });
    }
    
    // Debounced search
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length === 0 || this.value.length >= 3) {
                    this.closest('form').submit();
                }
            }, 500);
        });
    }
});


</script>