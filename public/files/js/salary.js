// Global function for showing toast notifications
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else {
        // Fallback to alert if toastr is not available
        alert(message);
    }
}

// Function to load employee loans and calculate approved amount
function loadEmployeeLoans(employeeId) {
    if (!employeeId) {
        $('#approved_loans').val('0');
        $('#loan-info').hide();
        $('#calculation-summary').hide();
        return;
    }
    
    $.ajax({
        url: '/salary/employee-loans/' + employeeId,
        method: 'GET',
        success: function(response) {
            let totalApprovedAmount = 0;
            let loanDetails = '';
            
            if (response.loans && response.loans.length > 0) {
                loanDetails = '<ul class="mb-0">';
                response.loans.forEach(function(loan) {
                    totalApprovedAmount += parseFloat(loan.amount);
                    loanDetails += '<li><strong>' + loan.type + ':</strong> Pkr' + parseFloat(loan.amount).toFixed(2) + '</li>';
                });
                loanDetails += '</ul>';
                
                $('#loan-details').html(loanDetails);
                $('#loan-info').show();
            } else {
                $('#loan-info').hide();
            }
            
            $('#approved_loans').val(totalApprovedAmount.toFixed(2));
            
            // Auto-calculate final amount
            calculateFinalAmount();
            updateCalculationSummary();
        },
        error: function() {
            $('#approved_loans').val('0');
            $('#loan-info').hide();
            calculateFinalAmount();
            updateCalculationSummary();
        }
    });
}

// Function to calculate final amount
function calculateFinalAmount() {
    const baseAmount = parseFloat($('#base_amount').val()) || 0;
    const approvedLoans = parseFloat($('#approved_loans').val()) || 0;
    const finalAmount = baseAmount + approvedLoans;
    
    $('#final_amount').val(finalAmount.toFixed(2));
    updateCalculationSummary();
}

// Function to update calculation summary
function updateCalculationSummary() {
    const baseAmount = parseFloat($('#base_amount').val()) || 0;
    const approvedLoans = parseFloat($('#approved_loans').val()) || 0;
    const totalAmount = baseAmount + approvedLoans;
    
    $('#summary-base').text('Pkr' + baseAmount.toFixed(2));
    $('#summary-loans').text('Pkr' + approvedLoans.toFixed(2));
    $('#summary-total').text('Pkr' + totalAmount.toFixed(2));
    
    // Show summary if there are values
    if (baseAmount > 0 || approvedLoans > 0) {
        $('#calculation-summary').show();
    } else {
        $('#calculation-summary').hide();
    }
}

// Function to check for duplicate salary records
function checkForDuplicate() {
    const employeeId = $('#employee_id').val();
    const month = $('#month').val();
    const year = $('#year').val();
    const salaryId = $('input[name="_method"]').length ? window.location.pathname.split('/').pop() : null;
    
    // Remove any existing duplicate warning
    $('#duplicate-warning').hide();
    
    if (employeeId && month && year) {
        $.ajax({
            url: '/salary/check-duplicate',
            method: 'GET',
            data: {
                employee_id: employeeId,
                month: month,
                year: year,
                salary_id: salaryId
            },
            success: function(response) {
                if (response.exists) {
                    const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
                    const warningMessage = `A salary record already exists for this employee for ${monthName} ${year}.`;
                    $('#duplicate-message').text(warningMessage);
                    $('#duplicate-warning').show();
                    
                    // Disable submit button
                    $('#submit-btn').prop('disabled', true);
                } else {
                    // Enable submit button
                    $('#submit-btn').prop('disabled', false);
                }
            },
            error: function() {
                // Enable submit button on error (let server handle validation)
                $('#submit-btn').prop('disabled', false);
            }
        });
    }
}

// Initialize salary form functionality
function initializeSalaryForm() {
    // Check for employee loans when employee is selected
    $('#employee_id').on('change', function() {
        const employeeId = $(this).val();
        loadEmployeeLoans(employeeId);
        checkForDuplicate();
    });

    // Check for duplicates when month or year changes
    $('#month, #year').on('change', function() {
        checkForDuplicate();
    });

    // Auto-calculate final amount when base amount changes
    $('#base_amount').on('input', function() {
        calculateFinalAmount();
        // Add visual indicator that it's auto-calculated
        $('#final_amount').addClass('bg-light').attr('title', 'Auto-calculated');
    });

    // Allow manual override of final amount
    $('#final_amount').on('input', function() {
        // Remove auto-calculation indicator
        $(this).removeClass('bg-light').attr('title', 'Manually entered');
        updateCalculationSummary();
    });

    // Add focus event to show manual entry mode
    $('#final_amount').on('focus', function() {
        $(this).removeClass('bg-light').attr('title', 'You can manually edit this amount');
    });

    // Refresh loans button
    $('#refresh-loans').on('click', function() {
        const employeeId = $('#employee_id').val();
        if (employeeId) {
            $(this).find('i').addClass('fa-spin');
            loadEmployeeLoans(employeeId);
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 1000);
        } else {
            showToast('warning', 'Please select an employee first');
        }
    });

    // Form submission validation
    $('#salary-form').on('submit', function(e) {
        const employeeId = $('#employee_id').val();
        const month = $('#month').val();
        const year = $('#year').val();
        const salaryId = $('input[name="_method"]').length ? window.location.pathname.split('/').pop() : null;
        
        if (employeeId && month && year) {
            // Check if salary already exists for this employee, month, and year
            $.ajax({
                url: '/salary/check-duplicate',
                method: 'GET',
                data: {
                    employee_id: employeeId,
                    month: month,
                    year: year,
                    salary_id: salaryId
                },
                async: false, // Make it synchronous to prevent form submission
                success: function(response) {
                    if (response.exists) {
                        e.preventDefault();
                        const monthName = new Date(year, month - 1).toLocaleString('default', { month: 'long' });
                        showToast('error', `A salary record already exists for this employee for ${monthName} ${year}.`);
                        return false;
                    }
                },
                error: function() {
                    // If check fails, allow form submission (server will validate)
                    return true;
                }
            });
        }
    });

    // Initialize calculation summary on page load
    updateCalculationSummary();
}