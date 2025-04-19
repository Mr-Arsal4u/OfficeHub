
function editEmployee(employee) {
    $('#employee-form').attr('action', '/employee/' + employee.id); 
    $('#employee-form').attr('method', 'PUT'); 
    $('#first_name').val(employee.first_name);
    $('#last_name').val(employee.last_name);
    $('#email').val(employee.email);
    $('#phone').val(employee.phone);
    $('#position').val(employee.position);
    $('#department').val(employee.department);
    $('#salary').val(employee.salary);
    $('#hire_date').val(employee.hire_date);
    $('#user_id').val(employee.user_id);
    $('.modal-title').text('Edit Employee');
    
}


function clearModalForm() {
    $('#employee-form').trigger('reset');
    $('#employee-form').attr('action', '/employee/store');
    $('#employee-form').attr('method', 'POST');
    $('.modal-title').text('Add New Employee');
}


$('#employee-form').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize(); 

    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: formData,
        success: function (response) {
            if (response.success) {
                $('#modals-slide-in').modal('hide');
                showToast("success", response.success); 
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