/**
 * CRUD Utilities - Enhanced form handling with validation and no-reload functionality
 */

class CrudUtils {
    constructor() {
        this.csrfToken = $('meta[name="csrf-token"]').attr('content');
        this.init();
    }

    init() {
        this.setupFormHandlers();
        this.setupRequiredFieldIndicators();
        this.setupValidationErrorHandling();
    }

    /**
     * Setup form handlers for all CRUD forms
     */
    setupFormHandlers() {
        // Employee form
        $('#employee-form').on('submit', (e) => this.handleFormSubmit(e, 'employee'));
        
        // Attendance form
        $('#attendance-form').on('submit', (e) => this.handleFormSubmit(e, 'attendance'));
        
        // Salary form
        $('#salary-form').on('submit', (e) => this.handleFormSubmit(e, 'salary'));
        
        // Expense form
        $('#expense-form').on('submit', (e) => this.handleFormSubmit(e, 'expense'));
        
        // Loan form
        $('#loan-form').on('submit', (e) => this.handleFormSubmit(e, 'loan'));
    }

    /**
     * Add asterisks to required fields
     */
    setupRequiredFieldIndicators() {
        $('input[required], select[required], textarea[required]').each(function() {
            const label = $(this).prev('label');
            if (label.length && !label.text().includes('*')) {
                label.html(label.text() + ' <span class="text-danger">*</span>');
            }
        });
    }

    /**
     * Setup validation error handling
     */
    setupValidationErrorHandling() {
        // Clear validation errors when input changes
        $(document).on('input change', 'input, select, textarea', function() {
            const fieldName = $(this).attr('name');
            if (fieldName) {
                this.clearFieldError(fieldName);
            }
        }.bind(this));
    }

    /**
     * Handle form submission with AJAX
     */
    handleFormSubmit(e, formType) {
        e.preventDefault();
        
        const form = $(e.target);
        const submitBtn = form.find('button[type="submit"]');
        
        // Prevent double submission
        if (submitBtn.prop('disabled')) {
            return false;
        }
        
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        
        // Clear previous errors
        this.clearAllErrors();
        
        // Show loading state and disable submit button
        this.showLoadingState(form);
        
        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': this.csrfToken
            },
            success: (response) => {
                this.handleSuccess(response, form, formType);
            },
            error: (xhr) => {
                this.handleError(xhr, form);
            },
            complete: () => {
                this.hideLoadingState(form);
            }
        });
    }

    /**
     * Handle successful form submission
     */
    handleSuccess(response, form, formType) {
        // Hide modal
        const modal = form.closest('.modal');
        if (modal.length) {
            modal.modal('hide');
        }
        
        // Show success message
        if (response.success) {
            showToast('success', response.success);
        }
        
        // Reset form
        form[0].reset();
        
        // Update table data without page reload
        this.updateTableData(formType);
        
        // Reset form action for create mode
        this.resetFormAction(form, formType);
    }

    /**
     * Handle form submission errors
     */
    handleError(xhr, form) {
        if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            this.displayValidationErrors(errors);
            showToast('error', 'Please fix the validation errors below.');
        } else {
            // Other errors
            const errorMessage = xhr.responseJSON?.error || 'An error occurred. Please try again.';
            showToast('error', errorMessage);
        }
    }

    /**
     * Display validation errors under input fields
     */
    displayValidationErrors(errors) {
        // Clear previous errors first
        this.clearAllErrors();
        
        Object.keys(errors).forEach(fieldName => {
            const field = $(`[name="${fieldName}"]`);
            if (field.length) {
                const errorMessage = errors[fieldName][0];
                this.showFieldError(fieldName, errorMessage);
                
                // Add error class to field
                field.addClass('is-invalid');
                
                // Scroll to first error
                if (Object.keys(errors)[0] === fieldName) {
                    field[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    /**
     * Show error message for a specific field
     */
    showFieldError(fieldName, message) {
        const field = $(`[name="${fieldName}"]`);
        if (field.length) {
            // Remove existing error message
            field.siblings('.invalid-feedback').remove();
            
            // Add error message
            const errorDiv = $(`<div class="invalid-feedback">${message}</div>`);
            field.after(errorDiv);
        }
    }

    /**
     * Clear error for a specific field
     */
    clearFieldError(fieldName) {
        const field = $(`[name="${fieldName}"]`);
        if (field.length) {
            field.removeClass('is-invalid');
            field.siblings('.invalid-feedback').remove();
        }
    }

    /**
     * Clear all validation errors
     */
    clearAllErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }

    /**
     * Show loading state
     */
    showLoadingState(form) {
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
        
        // Disable all form inputs during submission
        form.find('input, select, textarea, button').prop('disabled', true);
    }

    /**
     * Hide loading state
     */
    hideLoadingState(form) {
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', false);
        submitBtn.html('Submit');
        
        // Re-enable all form inputs after submission
        form.find('input, select, textarea').prop('disabled', false);
    }

    /**
     * Update table data without page reload
     */
    updateTableData(formType) {
        // Get current page URL to refresh table data
        const currentUrl = window.location.pathname;
        
        $.ajax({
            url: currentUrl,
            method: 'GET',
            success: (response) => {
                // Extract table content from response
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                const newTable = doc.querySelector('.table-responsive');
                
                if (newTable) {
                    $('.table-responsive').html(newTable.innerHTML);
                }
            }
        });
    }

    /**
     * Reset form action for create mode
     */
    resetFormAction(form, formType) {
        const baseUrl = this.getBaseUrl(formType);
        form.attr('action', baseUrl + '/store');
        form.attr('method', 'POST');
        
        // Reset modal title
        const modalTitle = form.closest('.modal').find('.modal-title');
        modalTitle.text('Add New ' + this.capitalizeFirst(formType));
    }

    /**
     * Get base URL for form type
     */
    getBaseUrl(formType) {
        const urls = {
            'employee': '/employee',
            'attendance': '/attendance',
            'salary': '/salary',
            'expense': '/expense',
            'loan': '/loan'
        };
        return urls[formType] || '';
    }

    /**
     * Capitalize first letter
     */
    capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    /**
     * Setup edit functionality for forms
     */
    setupEditFunction(data, formType) {
        const form = $(`#${formType}-form`);
        const modal = form.closest('.modal');
        
        // Set form action for update
        const baseUrl = this.getBaseUrl(formType);
        form.attr('action', baseUrl + '/' + data.id);
        form.attr('method', 'PUT');
        
        // Add method override for PUT
        if (!form.find('input[name="_method"]').length) {
            form.append('<input type="hidden" name="_method" value="PUT">');
        }
        
        // Populate form fields
        Object.keys(data).forEach(key => {
            const field = form.find(`[name="${key}"]`);
            if (field.length) {
                field.val(data[key]);
            }
        });
        
        // Update modal title
        modal.find('.modal-title').text('Edit ' + this.capitalizeFirst(formType));
        
        // Show modal
        modal.modal('show');
    }

    /**
     * Handle delete operations
     */
    handleDelete(url, formType) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: url,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                },
                success: (response) => {
                    showToast('success', response.success || 'Item deleted successfully');
                    this.updateTableData(formType);
                },
                error: (xhr) => {
                    const errorMessage = xhr.responseJSON?.error || 'An error occurred while deleting.';
                    showToast('error', errorMessage);
                }
            });
        }
    }
}

// Initialize CRUD utilities when document is ready
$(document).ready(function() {
    window.crudUtils = new CrudUtils();
});

// Global functions for backward compatibility
function editEmployee(employee) {
    window.crudUtils.setupEditFunction(employee, 'employee');
}

function editAttendance(attendance) {
    window.crudUtils.setupEditFunction(attendance, 'attendance');
}

function editSalary(salary) {
    window.crudUtils.setupEditFunction(salary, 'salary');
}

function editExpense(expense) {
    window.crudUtils.setupEditFunction(expense, 'expense');
}

function editLoan(loan) {
    window.crudUtils.setupEditFunction(loan, 'loan');
}

function deleteItem(url, formType) {
    window.crudUtils.handleDelete(url, formType);
}
