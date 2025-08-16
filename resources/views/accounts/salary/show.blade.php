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
                            <h2 class="content-header-title float-start mb-0">Salary Details</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('salary.index') }}">Salaries</a></li>
                                    <li class="breadcrumb-item active">Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Salary Information - {{ $salary->formatted_period }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Employee Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>{{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $salary->employee->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position:</strong></td>
                                            <td>{{ $salary->employee->position ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Department:</strong></td>
                                            <td>{{ $salary->employee->department ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Salary Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Period:</strong></td>
                                            <td>{{ $salary->formatted_period }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Base Amount:</strong></td>
                                            <td>${{ number_format($salary->base_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Final Amount:</strong></td>
                                            <td>${{ number_format($salary->final_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>{!! $salary->status_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Date:</strong></td>
                                            <td>{{ $salary->payment_date ? $salary->payment_date->format('M d, Y') : 'Not Paid' }}</td>
                                        </tr>
                                        @if($salary->description)
                                        <tr>
                                            <td><strong>Description:</strong></td>
                                            <td>{{ $salary->description }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($salary->payments->count() > 0)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h5>Payment Breakdown</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Payment Type</th>
                                                    <th>Amount</th>
                                                    <th>Payment Date</th>
                                                    <th>Status</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($salary->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->payment_type_label }}</td>
                                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                                    <td>{!! $payment->status_badge !!}</td>
                                                    <td>{{ $payment->notes }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($salary->loans->count() > 0)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <h5>Related Loans</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Loan Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Created Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($salary->loans as $loan)
                                                <tr>
                                                    <td>{{ $loan->type->value }}</td>
                                                    <td>${{ number_format($loan->amount, 2) }}</td>
                                                    <td>{!! $loan->status_badge !!}</td>
                                                    <td>{{ $loan->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('salary.index') }}" class="btn btn-secondary">Back to List</a>
                            @if($salary->status === 'pending')
                            <button class="btn btn-success" onclick="markAsPaid({{ $salary->id }})">Mark as Paid</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function markAsPaid(salaryId) {
            if (confirm('Are you sure you want to mark this salary as paid?')) {
                $.ajax({
                    url: '/salary/' + salaryId + '/mark-paid',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_date: new Date().toISOString().split('T')[0]
                    },
                    success: function(response) {
                        toastr.success(response.success);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.error || 'An error occurred');
                    }
                });
            }
        }
    </script>
@endpush
