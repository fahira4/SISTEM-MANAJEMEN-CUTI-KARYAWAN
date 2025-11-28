<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeMultipleSelection();
    initializeIndividualActions();
    initializeBulkActions();
});

function initializeMultipleSelection() {
    const selectAllCheckbox = document.getElementById('select-all');
    const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
    const selectedCountSpan = document.getElementById('selected-count');
    const countSpan = document.getElementById('count');
    const bulkToolbar = document.getElementById('bulk-toolbar');

    if (!selectAllCheckbox || !applicationCheckboxes.length) return;

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        applicationCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectionState();
    });

    // Individual checkbox change
    applicationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectionState();
        });
    });

    function updateSelectionState() {
        const selectedCount = document.querySelectorAll('.application-checkbox:checked').length;
        
        if (countSpan) countSpan.textContent = selectedCount;
        
        if (selectedCount > 0) {
            if (selectedCountSpan) selectedCountSpan.classList.remove('hidden');
            if (bulkToolbar) bulkToolbar.classList.remove('hidden');
            enableBulkButtons(true);
        } else {
            if (selectedCountSpan) selectedCountSpan.classList.add('hidden');
            if (bulkToolbar) bulkToolbar.classList.add('hidden');
            enableBulkButtons(false);
        }

        // Update select all checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === applicationCheckboxes.length;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < applicationCheckboxes.length;
        }
    }

    function enableBulkButtons(enabled) {
        const bulkApproveBtn = document.getElementById('bulk-approve-btn');
        const bulkRejectBtn = document.getElementById('bulk-reject-btn');
        
        if (bulkApproveBtn) bulkApproveBtn.disabled = !enabled;
        if (bulkRejectBtn) bulkRejectBtn.disabled = !enabled;
    }

    // Initial state
    updateSelectionState();
}

function initializeIndividualActions() {
    // Individual Approve buttons
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const applicationId = this.dataset.applicationId;
            const userName = this.dataset.userName;
            showApproveModal(applicationId, userName);
        });
    });

    // Individual Reject buttons
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const applicationId = this.dataset.applicationId;
            const userName = this.dataset.userName;
            showRejectModal(applicationId, userName);
        });
    });
}

function initializeBulkActions() {
    const bulkApproveBtn = document.getElementById('bulk-approve-btn');
    const bulkRejectBtn = document.getElementById('bulk-reject-btn');

    if (bulkApproveBtn) {
        bulkApproveBtn.addEventListener('click', function() {
            const selectedIds = getSelectedApplicationIds();
            if (selectedIds.length > 0) {
                showBulkApproveModal(selectedIds);
            } else {
                alert('Pilih setidaknya satu pengajuan untuk disetujui.');
            }
        });
    }

    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function() {
            const selectedIds = getSelectedApplicationIds();
            if (selectedIds.length > 0) {
                showBulkRejectModal(selectedIds);
            } else {
                alert('Pilih setidaknya satu pengajuan untuk ditolak.');
            }
        });
    }
}

function getSelectedApplicationIds() {
    const selectedCheckboxes = document.querySelectorAll('.application-checkbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => checkbox.value);
}

function getSelectedApplicationNames() {
    const selectedCheckboxes = document.querySelectorAll('.application-checkbox:checked');
    return Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.userName);
}

// Individual Modal Functions
function showApproveModal(applicationId, userName) {
    const namePlaceholder = document.getElementById('approveNamePlaceholder');
    if (namePlaceholder) namePlaceholder.textContent = userName;

    const form = document.getElementById('approveForm');
    if (form) form.action = `/leave-applications/${applicationId}/approve`;

    const modal = document.getElementById('approveModal');
    if (modal) modal.classList.remove('hidden');
}

function showRejectModal(applicationId, userName) {
    const namePlaceholder = document.getElementById('rejectNamePlaceholder');
    if (namePlaceholder) namePlaceholder.textContent = userName;

    const form = document.getElementById('rejectForm');
    if (form) form.action = `/leave-applications/${applicationId}/reject`;

    // Reset form state
    const rejectionNotes = document.getElementById('rejection_notes');
    if (rejectionNotes) rejectionNotes.value = '';
    
    const errorElement = document.getElementById('rejectionNotesError');
    if (errorElement) errorElement.classList.add('hidden');
    
    const submitBtn = document.getElementById('rejectSubmitBtn');
    if (submitBtn) submitBtn.disabled = true;
    
    const charCount = document.getElementById('currentChars');
    if (charCount) charCount.textContent = '0';

    const modal = document.getElementById('rejectModal');
    if (modal) modal.classList.remove('hidden');
}

// Bulk Modal Functions
function showBulkApproveModal(applicationIds) {
    const countElement = document.getElementById('bulkApproveCount');
    if (countElement) countElement.textContent = applicationIds.length;

    // Set nilai untuk form bulk approve
    const bulkApproveIdsInput = document.getElementById('bulk_approve_ids');
    if (bulkApproveIdsInput) {
        bulkApproveIdsInput.value = applicationIds.join(',');
    }

    const modal = document.getElementById('bulkApproveModal');
    if (modal) modal.classList.remove('hidden');
}

function showBulkRejectModal(applicationIds) {
    const countElement = document.getElementById('bulkRejectCount');
    if (countElement) countElement.textContent = applicationIds.length;

    // Set nilai untuk form bulk reject
    const bulkRejectIdsInput = document.getElementById('bulk_reject_ids');
    if (bulkRejectIdsInput) {
        bulkRejectIdsInput.value = applicationIds.join(',');
    }

    // Reset form state
    const bulkRejectionNotes = document.getElementById('bulk_rejection_notes');
    if (bulkRejectionNotes) bulkRejectionNotes.value = '';
    
    const errorElement = document.getElementById('bulkRejectionNotesError');
    if (errorElement) errorElement.classList.add('hidden');
    
    const submitBtn = document.getElementById('bulkRejectSubmitBtn');
    if (submitBtn) submitBtn.disabled = true;
    
    const charCount = document.getElementById('bulkCurrentChars');
    if (charCount) charCount.textContent = '0';

    const modal = document.getElementById('bulkRejectModal');
    if (modal) modal.classList.remove('hidden');
}

// Modal Close Functions
function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    if (modal) modal.classList.add('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal) modal.classList.add('hidden');
}

function closeBulkApproveModal() {
    const modal = document.getElementById('bulkApproveModal');
    if (modal) modal.classList.add('hidden');
}

function closeBulkRejectModal() {
    const modal = document.getElementById('bulkRejectModal');
    if (modal) modal.classList.add('hidden');
}

// Validation Functions
function validateRejectionNotes(text, submitBtnId, errorId, charCountId) {
    const submitBtn = document.getElementById(submitBtnId);
    const errorElement = document.getElementById(errorId);
    const charCount = document.getElementById(charCountId);

    if (!text || text.length < 10) {
        if (submitBtn) submitBtn.disabled = true;
        if (errorElement) errorElement.classList.remove('hidden');
    } else {
        if (submitBtn) submitBtn.disabled = false;
        if (errorElement) errorElement.classList.add('hidden');
    }

    if (charCount) charCount.textContent = text.length;
}

// Initialize form validations
document.addEventListener('DOMContentLoaded', function() {
    // Rejection notes validation for individual reject
    const rejectionNotes = document.getElementById('rejection_notes');
    if (rejectionNotes) {
        rejectionNotes.addEventListener('input', function() {
            validateRejectionNotes(this.value, 'rejectSubmitBtn', 'rejectionNotesError', 'currentChars');
        });
    }

    // Bulk rejection notes validation
    const bulkRejectionNotes = document.getElementById('bulk_rejection_notes');
    if (bulkRejectionNotes) {
        bulkRejectionNotes.addEventListener('input', function() {
            validateRejectionNotes(this.value, 'bulkRejectSubmitBtn', 'bulkRejectionNotesError', 'bulkCurrentChars');
        });
    }
});
</script>