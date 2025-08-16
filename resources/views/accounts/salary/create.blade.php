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
                            <h2 class="content-header-title float-start mb-0">Create Salary Record</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('salary.index') }}">Salaries</a></li>
                                    <li class="breadcrumb-item active">Create</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1">
                        <a href="{{ route('salary.index') }}" class="btn btn-outline-secondary">
                            <i data-feather="arrow-left" class="me-1"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add New Salary Record</h4>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('salary.store') }}" method="POST" id="salary-form">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="employee_id">Employee <span class="text-danger">*</span></label>
                                            <select class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                                <option value="" selected disabled>Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="month">Month <span class="text-danger">*</span></label>
                                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                                <option value="" selected disabled>Select Month</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ old('month') == $i ? 'selected' : '' }}>
                                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <small class="form-text text-muted">Only one salary record per employee per month</small>
                                            @error('month')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label" for="year">Year <span class="text-danger">*</span></label>
                                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                                <option value="" selected disabled>Select Year</option>
                                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                                    <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="base_amount">Base Amount <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control @error('base_amount') is-invalid @enderror" 
                                                    id="base_amount" name="base_amount" value="{{ old('base_amount') }}" required>
                                            </div>
                                            @error('base_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="approved_loans">Approved Loans/Advances</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control" id="approved_loans" 
                                                    name="approved_loans" value="0" readonly>
                                                <button type="button" class="btn btn-outline-secondary" id="refresh-loans" title="Refresh loan information">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">Auto-calculated from approved loans</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="final_amount">Final Amount <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" class="form-control @error('final_amount') is-invalid @enderror" 
                                                    id="final_amount" name="final_amount" value="{{ old('final_amount') }}" required>
                                            </div>
                                            <small class="form-text text-muted">Base + Loans/Advances</small>
                                            @error('final_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="payment_date">Payment Date</label>
                                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                                id="payment_date" name="payment_date" value="{{ old('payment_date') }}">
                                            @error('payment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="status">Status</label>
                                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                        id="description" cols="10" rows="3" placeholder="Enter any additional notes or description...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Loan Information Section -->
                                <div class="mb-3" id="loan-info" style="display: none;">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-1"></i>Approved Loans & Advances</h6>
                                        <div id="loan-details"></div>
                                        <small class="text-muted">These amounts will be automatically added to the final salary.</small>
                                    </div>
                                </div>
                                
                                <!-- Calculation Summary -->
                                <div class="mb-3" id="calculation-summary" style="display: none;">
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-calculator me-1"></i>Salary Calculation Summary</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Base Salary:</strong> <span id="summary-base">$0.00</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Approved Loans:</strong> <span id="summary-loans">$0.00</span>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Total Amount:</strong> <span id="summary-total">$0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Duplicate Warning -->
                                <div class="mb-3" id="duplicate-warning" style="display: none;">
                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Warning</h6>
                                        <div id="duplicate-message"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('salary.index') }}" class="btn btn-outline-secondary">
                                        <i data-feather="x" class="me-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submit-btn">
                                        <i data-feather="save" class="me-1"></i> Create Salary Record
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
<script src="{{ asset('files/js/salary.js') }}"></script>
<script>
$(document).ready(function() {
    // Set default values
    $('#month').val('{{ date("n") }}');
    $('#year').val('{{ date("Y") }}');
    $('#status').val('pending');
    
    // Initialize form functionality
    initializeSalaryForm();
});
</script>
@endpush
