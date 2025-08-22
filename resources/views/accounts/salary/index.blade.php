@extends('layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Salary Management</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Salaries</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1">
                        <a href="{{ route('salary.create') }}" class="btn btn-primary">
                            <i data-feather="plus" class="me-1"></i> Add Salary
                        </a>
                    </div>
                </div>
            </div>

            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center mt-50">
                            <h4 class="card-title">Salary Records</h4>

                        </div>
                        <div class="card-body">
                            <!-- Summary Statistics -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary rounded me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="dollar-sign" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ $salaries->count() }}</h4>
                                                    <small class="text-muted">Total Records</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-success">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-success rounded me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="check-circle" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ $salaries->where('status', 'paid')->count() }}
                                                    </h4>
                                                    <small class="text-muted">Paid Salaries</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-warning rounded me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="clock" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ $salaries->where('status', 'pending')->count() }}
                                                    </h4>
                                                    <small class="text-muted">Pending Salaries</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-info">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-info rounded me-2">
                                                    <div class="avatar-content">
                                                        <i data-feather="trending-up" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">
                                                        Pkr{{ number_format($salaries->sum('final_amount'), 0) }}</h4>
                                                    <small class="text-muted">Total Amount</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="salary-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Period</th>
                                        <th>Base Amount</th>
                                        <th>Final Amount</th>
                                        <th>Status</th>
                                        <th>Payment Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salaries as $salary)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar rounded me-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="user" class="font-medium-3"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bolder">
                                                            {{ optional($salary->employee)->first_name ?? 'N/A' }}
                                                            {{ optional($salary->employee)->last_name ?? 'N/A' }}</div>
                                                        <small
                                                            class="text-bold">{{ optional($salary->employee)->email ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-bold fw-bolder">{{ $salary->formatted_period }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-success fw-bolder">Pkr{{ number_format($salary->base_amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-primary fw-bolder">Pkr{{ number_format($salary->final_amount, 2) }}</span>
                                            </td>
                                            <td>
                                               <span
                                                    class="text-bold fw-bolder"> {!! $salary->status !!} </span>
                                            </td>
                                            <td>
                                                @if ($salary->payment_date)
                                                    <span
                                                        class="text-muted">{{ $salary->payment_date->format('M d, Y') }}</span>
                                                @else
                                                    <span class="text-muted">Not Paid</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                        data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical" class="font-medium-3"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{ route('salary.show', $salary->id) }}"
                                                            class="dropdown-item">
                                                            <i data-feather="eye" class="me-50"></i>
                                                            <span>View Details</span>
                                                        </a>
                                                        <a href="{{ route('salary.edit', $salary->id) }}"
                                                            class="dropdown-item">
                                                            <i data-feather="edit" class="me-50"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                        @if ($salary->status === 'pending')
                                                            <a href="#" class="dropdown-item mark-paid-btn"
                                                                data-salary-id="{{ $salary->id }}">
                                                                <i data-feather="check-circle" class="me-50"></i>
                                                                <span>Mark as Paid</span>
                                                            </a>
                                                        @endif
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#"
                                                            class="dropdown-item text-danger delete-salary-btn"
                                                            data-salary-id="{{ $salary->id }}">
                                                            <i data-feather="trash-2" class="me-50"></i>
                                                            <span>Delete</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No salary records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportToExcel() {
            // Implementation for Excel export
            alert('Excel export functionality will be implemented here');
        }

        function exportToPDF() {
            // Implementation for PDF export
            alert('PDF export functionality will be implemented here');
        }

        // Initialize DataTable
        $(document).ready(function() {
            $('#salary-table').DataTable({
                pageLength: 25,
                order: [
                    [1, 'desc']
                ], // Sort by period descending
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

        // Delete salary functionality
        $('.delete-salary-btn').on('click', function(e) {
            e.preventDefault();
            const salaryId = $(this).data('salary-id');

            if (confirm(
                    'Are you sure you want to delete this salary record? This action cannot be undone.'
                )) {
                $.ajax({
                    url: `/salary/${salaryId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast('success', response.success);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showToast('error', xhr.responseJSON?.error ||
                            'An error occurred while deleting the salary.');
                    }
                });
            }
        });

        // Mark as paid functionality
        $('.mark-paid-btn').on('click', function(e) {
            e.preventDefault();
            const salaryId = $(this).data('salary-id');
            console.log('hello');

            if (confirm('Are you sure you want to mark this salary as paid?')) {
                $.ajax({
                    url: `/salary/${salaryId}/mark-paid`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        payment_date: new Date().toISOString().split('T')[0]
                    },
                    success: function(response) {
                        showToast('success', response.success);
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        showToast('error', xhr.responseJSON?.error ||
                            'An error occurred while marking salary as paid.');
                    }
                });
            }
        });

        // Toast notification function
        function showToast(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }
    </script>
@endpush
