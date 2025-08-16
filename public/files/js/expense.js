// Form submission is now handled by crud-utils.js
// This file now only contains expense-specific functionality

function editExpense(expense) {
    window.crudUtils.setupEditFunction(expense, 'expense');
}

function clearModalForm() {
    $('#expense-form').trigger('reset');
    $('#expense-form').attr('action', '/expense/store');
    $('#expense-form').attr('method', 'POST');
    $('.modal-title').text('Add New Expense');
}

// Clear modal form when modal is hidden
$('#expense-modal').on('hidden.bs.modal', function () {
    clearModalForm();
});
