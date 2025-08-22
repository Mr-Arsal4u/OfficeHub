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
                            <h2 class="content-header-title float-start mb-0">Create Payment Request</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('request.payment') }}">Payment
                                            Requests</a></li>
                                    <li class="breadcrumb-item active">Create</li>
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
                            <h4 class="card-title">Add New Payment Request</h4>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('request.payment.store') }}" method="POST" id="payment-request-form">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="employee_id">Employee <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('employee_id') is-invalid @enderror"
                                                id="employee_id" name="employee_id" required>
                                                <option value="" selected disabled>Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                                        ({{ $employee->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="type">Request Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type"
                                                name="type" required>
                                                <option value="" selected disabled>Select Type</option>
                                                @foreach ($types as $type)
                                                    <option value="{{ $type->value }}"
                                                        {{ old('type') == $type->value ? 'selected' : '' }}>
                                                        {{ $type->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="amount">Amount <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Pkr</span>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    id="amount" name="amount" value="{{ old('amount') }}" required>
                                            </div>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="refund_percentage">Refund Percentage (If Any)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">%</span>
                                                <input type="number" step="0.01"
                                                    class="form-control @error('refund_percentage') is-invalid @enderror"
                                                    id="refund_percentage" max="100" min="0" name="refund_percentage" value="{{ old('refund_percentage') }}" required>
                                            </div>
                                            @error('refund_percentage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                                cols="10" rows="3" placeholder="Enter reason for payment request...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('request.payment') }}" class="btn btn-outline-secondary">
                                        <i data-feather="x" class="me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i data-feather="save" class="me-1"></i> Submit Request
                                    </button>
                                </div>
                            </form>
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
            // Disable submit button after click to prevent double submission
            $('#payment-request-form').on('submit', function() {
                const submitBtn = $('#submit-btn');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...');

                // Re-enable after 5 seconds if form submission fails
                setTimeout(function() {
                    submitBtn.prop('disabled', false);
                    submitBtn.html('<i data-feather="save" class="me-1"></i> Submit Request');
                }, 5000);
            });

            // Form validation
            $('#payment-request-form').on('submit', function(e) {
                const employeeId = $('#employee_id').val();
                const type = $('#type').val();
                const amount = $('#amount').val();

                if (!employeeId || !type || !amount) {
                    e.preventDefault();
                    showToast('error', 'Please fill in all required fields.');
                    return false;
                }

                if (parseFloat(amount) <= 0) {
                    e.preventDefault();
                    showToast('error', 'Amount must be greater than zero.');
                    return false;
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
