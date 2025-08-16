function editSalary(salary) {
    $('#salary-form').attr('action', '/request/payment/' + salary.id);
    $('#salary-form').attr('method', 'PUT');
    $('#employee_id').val(salary.employee_id);
    $('#amount').val(salary.amount);
    $("#type").val(salary.type);
    $('.modal-title').text('Edit Request');

}

function clearModalForm() {
    $('#salary-form').trigger('reset');
    $('#salary-form').attr('action', '/request/payment/store');
    $('#salary-form').attr('method', 'POST');
    $('.modal-title').text('Create New Request');
}


$('#salary-form').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize();

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: formData,
        success: function (response) {
            if (response.success) {
                $('#salary-modal').modal('hide');
                showToast('success', response.success);
                window.location.reload();
            } else {
                showToast("error", response.error);
            }
        },
        error: function (xhr, status, error) {
            showToast("error", error);
        }
    });
});