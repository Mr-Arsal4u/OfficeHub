function editExpense(expense) {
    console.log(expense);
    $('#expense-form').attr('action', '/expense/' + expense.id);
    $('#expense-form').attr('method', 'PUT');
    $("#category").val(expense.category);
    $("#amount").val(expense.amount);
    $("#description").val(expense.description);
    $("#date").val(expense.date);
    $('.modal-title').text('Edit Expense');
}

function clearModalForm() {
    $('#expense-form').trigger('reset');
    $('#expense-form').attr('action', '/expense/store');
    $('#expense-form').attr('method', 'POST');
    $('.modal-title').text('Add New expense');
}

$('#expense-form').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: formData,
        success: function (response) {
            if (response.success) {
                $('#expense-modal').modal('hide');
                showToast('success', response.success);

            } else {
                showToast("error", response.error);
            }
        },
        error: function (xhr, status, error) {
            showToast("error", error);

        }
    });
});
