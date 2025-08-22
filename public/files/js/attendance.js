$(document).ready(function () {
    // --- Helpers ------------------------------------------------------------
    function enableModalTimes() {
        $(".check_in, .check_out")
            .show()
            .find("input, select, textarea")
            .prop("disabled", false);
    }

    function disableModalTimes(clearValues = true) {
        const $targets = $(".check_in, .check_out")
            .hide()
            .find("input, select, textarea")
            .prop("disabled", true);
        if (clearValues) $targets.val("");
    }

    function toggleModalTimesByStatus(status) {
        if (status === "present" || status === "leave") {
            enableModalTimes();
        } else {
            disableModalTimes(true); // absent → disable & clear
        }
    }

    // For inline rows
    function toggleRowTimesByStatus($row, status) {
        const $inputs = $row.find(".check-in-input, .check-out-input");
        if (status === "present" || status === "leave") {
            $inputs.prop("disabled", false);
            $row.find(".check-in-wrapper, .check-out-wrapper").show();
        } else {
            $inputs.prop("disabled", true).val("");
            $row.find(".check-in-wrapper, .check-out-wrapper").hide();
        }
    }

    // --- Modal: status change ----------------------------------------------
    $("#status").on("change", function () {
        toggleModalTimesByStatus($(this).val());
    });

    // Initialize modal visibility/state on load
    toggleModalTimesByStatus($("#status").val());

    // --- Edit Attendance (modal fill) --------------------------------------
    window.editAttendance = function (employee) {
        const attendance = employee.attendance;

        if (!attendance) {
            console.error("No attendance data available for this employee.");
            return;
        }

        $('#attendance-form').attr('action', '/attendance/' + attendance.id);
        $('#attendance-form').attr('method', 'PUT'); // if you use method spoofing, keep a _method=PUT hidden field

        $('#attendance_id').val(attendance.id);
        $('#employee_id').val(employee.id);
        $('#date').val(attendance.date);

        $('#status').val(attendance.status);
        $("#check_in_time").val(attendance.check_in_time || "");
        $("#check_out_time").val(attendance.check_out_time || "");

        toggleModalTimesByStatus(attendance.status);

        $('.modal-title').text('Edit Attendance');
    };

    function clearModalForm() {
        $('#attendance-form').trigger('reset');
        $('#attendance-form').attr('action', '/attendance/store');
        $('#attendance-form').attr('method', 'POST');
        $('.modal-title').text('Add New Attendance');
        // Ensure defaults after reset
        toggleModalTimesByStatus($("#status").val());
    }

    // --- Date filter --------------------------------------------------------
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

    // --- Delegated handlers for dynamic rows --------------------------------
    // Status dropdown change (inline table)
    $(document).on('change', '.status-dropdown', function () {
        const $row = $(this).closest('tr');
        const employeeId = $(this).data('employee-id') || $row.data('employee-id');
        const status = $(this).val();

        // Toggle row inputs based on status
        toggleRowTimesByStatus($row, status);

        // Prepare payload using current row values
        const payload = { status: status };

        const rowDate = $('#attendance-date-filter').val();
        if (rowDate) payload.date = rowDate;

        // If absent, times were cleared by toggleRowTimesByStatus
        const checkInTime = $row.find('.check-in-input').val();
        const checkOutTime = $row.find('.check-out-input').val();

        if (checkInTime) payload.check_in_time = checkInTime;
        if (checkOutTime) payload.check_out_time = checkOutTime;

        updateAttendance(employeeId, payload);
    });

    // Check-in input change (inline table)
    $(document).on('change', '.check-in-input', function () {
        const $row = $(this).closest('tr');
        const employeeId = $(this).data('employee-id') || $row.data('employee-id');

        const payload = {
            check_in_time: $(this).val()
        };

        const rowDate = $('#attendance-date-filter').val();
        if (rowDate) payload.date = rowDate;

        // Ensure we also send status so backend can validate transitions if needed
        const status = $row.find('.status-dropdown').val();
        if (status) payload.status = status;

        updateAttendance(employeeId, payload);
    });

    // Check-out input change (inline table)
    $(document).on('change', '.check-out-input', function () {
        const $row = $(this).closest('tr');
        const employeeId = $(this).data('employee-id') || $row.data('employee-id');

        const payload = {
            check_out_time: $(this).val()
        };

        const rowDate = $('#attendance-date-filter').val();
        if (rowDate) payload.date = rowDate;

        const status = $row.find('.status-dropdown').val();
        if (status) payload.status = status;

        updateAttendance(employeeId, payload);
    });

    // --- AJAX updater (shared) ---------------------------------------------
    function updateAttendance(employeeId, data) {
        const date = $('#attendance-date-filter').val();
        if (date && !data.date) data.date = date;

        $.ajax({
            url: '/attendance/update/ajax',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                employee_id: employeeId,
                ...data
            },
            
            success: function (res) {
                showToast("success", res.success || 'Attendance updated.');
                // If you want to avoid full reloads, remove the next line:
                // window.location.reload();
            },
            error: function (err) {
                console.error('Error updating attendance', err);
                showToast("error", 'Error updating attendance.');
            }
        });
    }
});

// --- Toast helper -----------------------------------------------------------
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        alert(message);
    }
}

// --- Set status for ALL employees ------------------------------------------
function setStatus(status) {
    if (!confirm('Are you sure you want to set all employees to ' + status + '?')) return;

    const selectedDate = $('#attendance-date-filter').val() || new Date().toISOString().split('T')[0];

    // Disable all "setStatus" buttons and show spinner
    const $buttons = $('button[onclick*="setStatus"]');
    $buttons.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...');

    $.ajax({
        url: '/attendance/update/all',
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: status,
            date: selectedDate
        },
        success: function (response) {
            showToast("success", response.success || 'Attendance updated successfully');
            setTimeout(function () {
                window.location.reload();
            }, 800);
        },
        error: function (xhr) {
            let errorMessage = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            showToast("error", errorMessage);
            console.error('Error updating attendance:', xhr);

            // Re-enable all buttons and restore their labels
            $buttons.prop('disabled', false).each(function () {
                // const $btn = $(this);
                // if ($btn.text().toLowerCase().includes('present')) {
                //     $btn.html('<i class="fas fa-check-circle me-1"></i> All Present');
                // } else if ($btn.text().toLowerCase().includes('absent')) {
                //     $btn.html('<i class="fas fa-times-circle me-1"></i> All Absent');
                // } else {
                //     $btn.html('<i class="fas fa-calendar-minus me-1"></i> All Leave');
                // }
            });
        }
    });
}
