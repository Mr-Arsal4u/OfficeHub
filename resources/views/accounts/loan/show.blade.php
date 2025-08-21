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
                            <h2 class="content-header-title float-start mb-0">Payment Request Details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('request.payment') }}">Payment Requests</a></li>
                                    <li class="breadcrumb-item active">Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1">
                        <a href="{{ route('request.payment') }}" class="btn btn-outline-secondary">
                            <i data-feather="arrow-left" class="me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Payment Request Information</h4>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">ID: {{ $loan->id }}</span>
                                <span class="badge bg-info me-2">{{ $loan->type->label() }}</span>
                                {!! $loan->status_badge !!}
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Employee Information -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i data-feather="user" class="me-2"></i>Employee Information
                                    </h5>
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="avatar rounded me-2">
                                                            <div class="avatar-content">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</h6>
                                                            <small class="text-muted">{{ $loan->employee->email }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <small class="text-muted">Phone</small>
                                                            <p class="mb-0">{{ $loan->employee->phone ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Position</small>
                                                            <p class="mb-0">{{ $loan->employee->position ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i data-feather="file-text" class="me-2"></i>Request Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card bg-light-success">
                                                <div class="card-body text-center">
                                                    <div class="avatar bg-success rounded mb-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="dollar-sign" class="text-white"></i>
                                                        </div>
                                                    </div>
                                                    <h4 class="mb-0">Pkr{{ number_format($loan->amount, 2) }}</h4>
                                                    <small class="text-muted">Requested Amount</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-light-info">
                                                <div class="card-body text-center">
                                                    <div class="avatar bg-info rounded mb-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="tag" class="text-white"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">{{ $loan->type->label() }}</h6>
                                                    <small class="text-muted">Request Type</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-light-warning">
                                                <div class="card-body text-center">
                                                    <div class="avatar bg-warning rounded mb-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="calendar" class="text-white"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">{{ $loan->created_at->format('M d, Y') }}</h6>
                                                    <small class="text-muted">Requested Date</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card bg-light-primary">
                                                <div class="card-body text-center">
                                                    <div class="avatar bg-primary rounded mb-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="clock" class="text-white"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-0">{{ $loan->created_at->format('h:i A') }}</h6>
                                                    <small class="text-muted">Requested Time</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Information -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i data-feather="activity" class="me-2"></i>Status Information
                                    </h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td><strong>Status:</strong></td>
                                                            <td>{!! $loan->status_badge !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Request Type:</strong></td>
                                                            <td><span class="badge bg-light-primary">{{ $loan->type->label() }}</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Amount:</strong></td>
                                                            <td><span class="text-primary fw-bolder">Pkr{{ number_format($loan->amount, 2) }}</span></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <td><strong>Created:</strong></td>
                                                            <td>{{ $loan->created_at->format('M d, Y h:i A') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Updated:</strong></td>
                                                            <td>{{ $loan->updated_at->format('M d, Y h:i A') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Days Pending:</strong></td>
                                                            <td>{{ $loan->created_at->diffInDays(now()) }} days</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($loan->description)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i data-feather="message-square" class="me-2"></i>Description
                                    </h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="mb-0">{{ $loan->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="{{ route('request.payment') }}" class="btn btn-outline-secondary">
                                                <i data-feather="arrow-left" class="me-1"></i> Back to List
                                            </a>
                                            @if(!$loan->employee->hasRole('Admin'))
                                            <a href="{{ route('request.payment.edit', $loan->id) }}" class="btn btn-primary">
                                                <i data-feather="edit" class="me-1"></i> Edit Request
                                            </a>
                                            @endif
                                        </div>
                                        <div>
                                            @if(auth()->user() && auth()->user()->hasRole('Admin'))
                                                @if($loan->is_approved->value == 0)
                                                <button class="btn btn-success approve-request-btn" data-request-id="{{ $loan->id }}">
                                                    <i data-feather="check-circle" class="me-1"></i> Approve Request
                                                </button>
                                                @else
                                                <button class="btn btn-warning reject-request-btn" data-request-id="{{ $loan->id }}">
                                                    <i data-feather="x-circle" class="me-1"></i> Reject Request
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
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
