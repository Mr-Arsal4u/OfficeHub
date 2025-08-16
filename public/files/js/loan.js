// Global function for showing toast notifications
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        // Fallback to alert if toastr is not available
        alert(message);
    }
}

// Initialize payment request form functionality
function initializePaymentRequestForm() {
    // Disable submit button after click to prevent double submission
    $('#payment-request-form').on('submit', function() {
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');
        
        // Re-enable after 5 seconds if form submission fails
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }, 5000);
    });

    // Form validation
    $('#payment-request-form').on('submit', function(e) {
        const employeeId = $('#employee_id').val();
        const type = $('#type').val();
        const amount = $('#amount').val();

        if (!employeeId || !type || !amount) {
            e.preventDefault();
            showToast('error', 'Please fill in all required fields.');
            return false;
        }

        if (parseFloat(amount) <= 0) {
            e.preventDefault();
            showToast('error', 'Amount must be greater than zero.');
            return false;
        }
    });

    // Real-time validation feedback
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val());
        const submitBtn = $('#submit-btn');
        
        if (amount <= 0) {
            $(this).addClass('is-invalid');
            submitBtn.prop('disabled', true);
        } else {
            $(this).removeClass('is-invalid');
            submitBtn.prop('disabled', false);
        }
    });

    // Employee selection validation
    $('#employee_id').on('change', function() {
        const employeeId = $(this).val();
        const submitBtn = $('#submit-btn');
        
        if (!employeeId) {
            submitBtn.prop('disabled', true);
        } else {
            submitBtn.prop('disabled', false);
        }
    });

    // Type selection validation
    $('#type').on('change', function() {
        const type = $(this).val();
        const submitBtn = $('#submit-btn');
        
        if (!type) {
            submitBtn.prop('disabled', true);
        } else {
            submitBtn.prop('disabled', false);
        }
    });
}

// Initialize form when document is ready
$(document).ready(function() {
    if ($('#payment-request-form').length) {
        initializePaymentRequestForm();
    }
});