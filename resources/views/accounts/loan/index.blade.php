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
                            <h2 class="content-header-title float-start mb-0">Payment Requests</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Payment Requests</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1">
                        <a href="{{ route('request.payment.create') }}" class="btn btn-primary">
                            <i data-feather="plus" class="me-1"></i> Add Payment Request
                        </a>
                    </div>
                </div>
            </div>

            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center mt-50">
                            <h4 class="card-title">Payment Requests</h4>
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
                                                        <i data-feather="file-text" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ $requests->count() }}</h4>
                                                    <small class="text-muted">Total Requests</small>
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
                                                    <h4 class="mb-0">{{ $requests->where('is_approved', 1)->count() }}</h4>
                                                    <small class="text-muted">Approved Requests</small>
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
                                                    <h4 class="mb-0">{{ $requests->where('is_approved', 0)->count() }}</h4>
                                                    <small class="text-muted">Pending Requests</small>
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
                                                        <i data-feather="dollar-sign" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">${{ number_format($requests->sum('amount'), 0) }}</h4>
                                                    <small class="text-muted">Total Amount</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="loan-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Request Type</th>
                                        <th>Amount Requested</th>
                                        <th>Requested Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar rounded me-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="user" class="font-medium-3"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bolder">{{ $request->employee->first_name ?? 'N/A' }} {{ $request->employee->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">{{ $request->employee->email ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light-primary">{{ $request->type->label() ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-primary fw-bolder">${{ number_format($request->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $request->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                {!! $request->status_badge !!}
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical" class="font-medium-3"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{ route('request.payment.show', $request->id) }}" class="dropdown-item">
                                                            <i data-feather="eye" class="me-50"></i>
                                                            <span>View Details</span>
                                                        </a>
                                                        @if(!$request->employee->hasRole('admin'))
                                                        <a href="{{ route('request.payment.edit', $request->id) }}" class="dropdown-item">
                                                            <i data-feather="edit" class="me-50"></i>
                                                            <span>Edit</span>
                                                        </a>
                                                        @endif
                                                        @if(auth()->user() && auth()->user()->hasRole('Admin'))
                                                            @if($request->is_approved->value == 0)
                                                            <a href="#" class="dropdown-item approve-request-btn" data-request-id="{{ $request->id }}">
                                                                <i data-feather="check-circle" class="me-50"></i>
                                                                <span>Approve</span>
                                                            </a>
                                                            @else
                                                            <a href="#" class="dropdown-item reject-request-btn" data-request-id="{{ $request->id }}">
                                                                <i data-feather="x-circle" class="me-50"></i>
                                                                <span>Reject</span>
                                                            </a>
                                                            @endif
                                                        @endif
                                                        @if(!$request->employee->hasRole('admin'))
                                                        <div class="dropdown-divider"></div>
                                                        <a href="#" class="dropdown-item text-danger delete-request-btn" data-request-id="{{ $request->id }}">
                                                            <i data-feather="trash-2" class="me-50"></i>
                                                            <span>Delete</span>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No payment requests found.</td>
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
    $('#loan-table').DataTable({
        pageLength: 25,
        order: [[3, 'desc']], // Sort by requested date descending
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Delete request functionality
    $('.delete-request-btn').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('request-id');
        
        if (confirm('Are you sure you want to delete this payment request? This action cannot be undone.')) {
            $.ajax({
                url: `/request/payment/${requestId}`,
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
                    showToast('error', xhr.responseJSON?.error || 'An error occurred while deleting the request.');
                }
            });
        }
    });

    // Approve request functionality
    $('.approve-request-btn').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('request-id');
        
        if (confirm('Are you sure you want to approve this payment request?')) {
            $.ajax({
                url: `/request/payment/update/status/${requestId}`,
                method: 'POST',
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
                    showToast('error', xhr.responseJSON?.error || 'An error occurred while approving the request.');
                }
            });
        }
    });

    // Reject request functionality
    $('.reject-request-btn').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('request-id');
        
        if (confirm('Are you sure you want to reject this payment request?')) {
            $.ajax({
                url: `/request/payment/update/status/${requestId}`,
                method: 'POST',
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
                    showToast('error', xhr.responseJSON?.error || 'An error occurred while rejecting the request.');
                }
            });
        }
    });
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
