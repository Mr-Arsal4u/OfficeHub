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

    // Form submission is now handled by crud-utils.js
    // This file now only contains attendance-specific functionality

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

// Global function for showing toast notifications
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        // Fallback to alert if toastr is not available
        alert(message);
    }
}

// Global function for setting status for all employees
function setStatus(status) {
    if (confirm('Are you sure you want to set all employees to ' + status + '?')) {
        let selectedDate = $('#attendance-date-filter').val() || new Date().toISOString().split('T')[0];
        
        // Disable buttons and show loading state
        $('button[onclick*="setStatus"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');
        
        $.ajax({
            url: '/attendance/update-all',
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: status,
                date: selectedDate
            },
            success: function (response) {
                showToast("success", response.success || 'Attendance updated successfully');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function (xhr) {
                let errorMessage = 'Something went wrong. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showToast("error", errorMessage);
                console.error('Error updating attendance:', xhr);
                
                // Re-enable buttons
                $('button[onclick*="setStatus(\'present\')"]').prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i> All Present');
                $('button[onclick*="setStatus(\'absent\')"]').prop('disabled', false).html('<i class="fas fa-times-circle me-1"></i> All Absent');
                $('button[onclick*="setStatus(\'leave\')"]').prop('disabled', false).html('<i class="fas fa-calendar-minus me-1"></i> All Leave');
            }
        });
    }
}