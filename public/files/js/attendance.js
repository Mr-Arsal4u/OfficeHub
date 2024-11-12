$(document).ready(function () {
    $("#status").change(function () {
        var selectedStatus = $(this).val();
        if (selectedStatus === "present") {
            $(".check_in, .check_out").show();
        } else {
            $(".check_in, .check_out").hide();
        }
    });

    if ($("#status").val() !== "present") {
        $(".check_in, .check_out").hide();
    }
});

function editAttendance(attendance) {
    $('#attendance-form').attr('action', '/attendance/' + attendance.id);
    $('#attendance-form').attr('method', 'PUT');

    $('#attendance_id').val(attendance.id);
    $('#employee_id').val(attendance.employee_id);
    $('#date').val(attendance.date);

    console.log('attendance.status:', attendance.status);

    $('#status').val(attendance.status);
    $("#check_in_time").val(attendance.check_in_time);
    $("#check_out_time").val(attendance.check_out_time);

    if (attendance.status === 'leave' || attendance.status === 'absent') {
        $(".check_in, .check_out").hide();
    } else {
        $(".check_in, .check_out").show();
    }
    // console.log(attendance);
    // console.log('hello u');
    $('.modal-title').text('Edit Attendance');
}


function clearModalForm() {
    $('#attendance-form').trigger('reset');
    $('#attendance-form').attr('action', '/attendance/store');
    $('#attendance-form').attr('method', 'POST');
    $('.modal-title').text('Add New Attendance');
}


$('#attendance-form').on('submit', function (e) {
    e.preventDefault();

    let formData = $(this).serialize();


    $.ajax({
        url: $(this).attr('action'),
        method: $(this).attr('method'),
        data: formData,
        success: function (response) {
            if (response.success) {
                // alert('Attendance saved successfully!');
                $('#attendance-modal').modal('hide');
                // location.reload();  
            } else {
                // alert('Error saving attendance!');
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            alert('Something went wrong!');
        }
    });
});

