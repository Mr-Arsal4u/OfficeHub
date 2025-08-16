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

    // Add this outside or at the top-level
    window.editAttendance = function (employee) {
        const attendance = employee.attendance;

        if (!attendance) {
            console.error("No attendance data available for this employee.");
            return;
        }

        $('#attendance-form').attr('action', '/attendance/' + attendance.id);
        $('#attendance-form').attr('method', 'PUT');

        $('#attendance_id').val(attendance.id);
        $('#employee_id').val(employee.id);
        $('#date').val(attendance.date);

        $('#status').val(attendance.status);
        $("#check_in_time").val(attendance.check_in_time);
        $("#check_out_time").val(attendance.check_out_time);

        if (attendance.status === 'leave' || attendance.status === 'absent') {
            $(".check_in, .check_out").hide();
        } else {
            $(".check_in, .check_out").show();
        }

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
                    $('#attendance-modal').modal('hide');
                    showToast("success", response.success);
                    window.location.reload();
                } else {
                    showToast("error", response.error);
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    // Validation errors
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    for (var field in errors) {
                        errorMessage += errors[field][0] + '\n';
                    }
                    showToast("error", errorMessage);
                } else {
                    showToast("error", "An error occurred while processing your request.");
                }
            }
        });
    });

    $('#attendance-date-filter').on('change', function () {
        let selectedDate = $(this).val();
        if (selectedDate) {
            $.ajax({
                url: '/attendance/filter-by-date',
                type: "GET",
                data: { date: selectedDate },
                success: function (response) {
                    $('#attendance-table').html(response);
                },
                error: function () {
                    alert('Failed to fetch data. Please try again.');
                }
            });
        }
    });

    function setStatus(status) {
        $.ajax({
            url: '/attendance/update-all',
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status
            },
            success: function (response) {
                window.location.reload();
                showToast("success", response.success);
            },
            error: function (xhr) {
                alert('Something went wrong');
            }
        });
    }

    $('.status-dropdown').on('change', function () {
        let employeeId = $(this).data('employee-id');
        let status = $(this).val();

        updateAttendance(employeeId, { status: status });
    });

    // Update check-in
    $('.check-in-input').on('change', function () {
        let employeeId = $(this).data('employee-id');
        let checkInTime = $(this).val();

        updateAttendance(employeeId, { check_in_time: checkInTime });
    });

    // Update check-out
    $('.check-out-input').on('change', function () {
        let employeeId = $(this).data('employee-id');
        let checkOutTime = $(this).val();

        updateAttendance(employeeId, { check_out_time: checkOutTime });
    });

    // Status dropdown change
    $(document).on('change', '.status-dropdown', function () {
        let employeeId = $(this).data('employee-id');
        let status = $(this).val();

        updateAttendance(employeeId, { status: status });
    });

    // Check-in input
    $(document).on('change', '.check-in-input', function () {
        let employeeId = $(this).data('employee-id');
        let checkInTime = $(this).val();

        updateAttendance(employeeId, { check_in_time: checkInTime });
    });

    // Check-out input
    $(document).on('change', '.check-out-input', function () {
        let employeeId = $(this).data('employee-id');
        let checkOutTime = $(this).val();

        updateAttendance(employeeId, { check_out_time: checkOutTime });
    });

    function updateAttendance(employeeId, data) {
        let date = $('#attendance-date-filter').val();

        if (date) {
            data.date = date;
        }

        $.ajax({
            url: '/attendance/update/ajax',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                employee_id: employeeId,
                ...data
            },
            success: function (res) {
                showToast("success", res.success);
                window.location.reload();
            },
            error: function (err) {
                console.error('Error updating attendance');
            }
        });
    }
});