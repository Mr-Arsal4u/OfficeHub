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
                            <h2 class="content-header-title float-start mb-0">Employee Report</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                                    <li class="breadcrumb-item active">Employee Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="content-header-right col-md-3 col-12 d-md-block d-none">
                    <div class="mb-1">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print Report
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center mt-50">
                            <h4 class="card-title">Employee Report - {{ $employee->first_name ?? 'N/A' }} {{ $employee->last_name ?? 'N/A' }}</h4>
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary">{{ $employee->id }}</span>
                                <span class="badge bg-info">{{ $employee->department ?? 'No Department' }}</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Employee Summary Cards -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="card bg-light-primary">
                                        <div class="card-body text-center">
                                            <i data-feather="user" class="font-large-2 text-primary mb-1"></i>
                                            <h5 class="card-title">Employee Info</h5>
                                            <p class="card-text">{{ $employee->first_name ?? 'N/A' }} {{ $employee->last_name ?? 'N/A' }}</p>
                                            <small class="text-muted">{{ $employee->position ?? 'No Position' }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-success">
                                        <div class="card-body text-center">
                                            <i data-feather="calendar" class="font-large-2 text-success mb-1"></i>
                                            <h5 class="card-title">Attendance</h5>
                                            <p class="card-text">{{ $attendanceSummary->present_days ?? 0 }} Days</p>
                                            <small class="text-muted">Present this month</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-warning">
                                        <div class="card-body text-center">
                                            <i data-feather="dollar-sign" class="font-large-2 text-warning mb-1"></i>
                                            <h5 class="card-title">Salary</h5>
                                            <p class="card-text">${{ number_format($monthlySalary ?? 0, 0) }}</p>
                                            <small class="text-muted">This month's salary</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light-info">
                                        <div class="card-body text-center">
                                            <i data-feather="trending-up" class="font-large-2 text-info mb-1"></i>
                                            <h5 class="card-title">Performance</h5>
                                            @php
                                                $totalDays = ($attendanceSummary->present_days ?? 0) + ($attendanceSummary->absent_days ?? 0) + ($attendanceSummary->leave_days ?? 0);
                                                $attendanceRate = $totalDays > 0 ? (($attendanceSummary->present_days ?? 0) / $totalDays) * 100 : 0;
                                            @endphp
                                            <p class="card-text">{{ number_format($attendanceRate, 1) }}%</p>
                                            <small class="text-muted">Attendance rate</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Personal Information -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i data-feather="user" class="me-1"></i>
                                                Personal Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Full Name:</strong></td>
                                                    <td>{{ $employee->first_name ?? 'N/A' }} {{ $employee->last_name ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>
                                                        <a href="mailto:{{ $employee->email ?? '' }}">{{ $employee->email ?? 'N/A' }}</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td>{{ $employee->phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Position:</strong></td>
                                                    <td>
                                                        @if($employee->position)
                                                            <span class="badge bg-primary">{{ $employee->position }}</span>
                                                        @else
                                                            <span class="badge bg-light-secondary">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Department:</strong></td>
                                                    <td>
                                                        @if($employee->department)
                                                            <span class="badge bg-success">{{ $employee->department }}</span>
                                                        @else
                                                            <span class="badge bg-light-secondary">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Hire Date:</strong></td>
                                                    <td>{{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('M d, Y') : 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Salary Information -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i data-feather="dollar-sign" class="me-1"></i>
                                                Salary Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="40%"><strong>Base Salary:</strong></td>
                                                    <td>
                                                        <span class="text-success fw-bolder">
                                                            ${{ number_format($employee->salary?->base_amount ?? 0, 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Current Status:</strong></td>
                                                    <td>
                                                        @if($employee->salary?->status)
                                                            @if($employee->salary->status === 'paid')
                                                                <span class="badge bg-success">Paid</span>
                                                            @elseif($employee->salary->status === 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @else
                                                                <span class="badge bg-danger">Cancelled</span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-light-secondary">No Salary Record</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Last Payment:</strong></td>
                                                    <td>
                                                        {{ $employee->salary?->payment_date ? \Carbon\Carbon::parse($employee->salary->payment_date)->format('M d, Y') : 'Not Paid' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Monthly Salary:</strong></td>
                                                    <td>
                                                        <span class="text-primary fw-bolder">
                                                            ${{ number_format($monthlySalary ?? 0, 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @if($employee->loans && $employee->loans->count() > 0)
                                                <tr>
                                                    <td><strong>Active Loans:</strong></td>
                                                    <td>
                                                        <span class="badge bg-warning">{{ $employee->loans->where('is_approved', \App\Enum\RequestIsApproved::YES)->sum('amount') }} Approved</span>
                                                    </td>
                                                </tr>
                                                @endif
                                                @if($employee->advance_payments && $employee->advance_payments->count() > 0)
                                                <tr>
                                                    <td><strong>Active Advance Payments:</strong></td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $employee->advance_payments->where('is_approved', \App\Enum\RequestIsApproved::YES)->sum('amount') }} Approved</span>
                                                    </td>
                                                </tr>
                                                @endif
                                                @if($employee->salary?->description)
                                                <tr>
                                                    <td><strong>Notes:</strong></td>
                                                    <td>{{ $employee->salary->description }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Summary -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">
                                                <i data-feather="calendar" class="me-1"></i>
                                                Attendance Summary
                                            </h5>
                                            <div class="d-flex gap-4">
                                                <input type="date" id="startDate" class="form-control form-control-sm" 
                                                    value="{{ request()->start_date ?? \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                                                <input type="date" id="endDate" class="form-control form-control-sm" 
                                                    value="{{ request()->end_date ?? \Carbon\Carbon::now()->endOfMonth()->toDateString() }}">
                                                <button class="btn btn-primary btn-sm" id="filterBtn">
                                                    <i data-feather="search" class="me-1"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-md-4">
                                                    <div class="border rounded p-3 bg-light-success">
                                                        <h3 class="text-success mb-1">{{ $attendanceSummary->present_days ?? 0 }}</h3>
                                                        <p class="mb-0">Present Days</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="border rounded p-3 bg-light-danger">
                                                        <h3 class="text-danger mb-1">{{ $attendanceSummary->absent_days ?? 0 }}</h3>
                                                        <p class="mb-0">Absent Days</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="border rounded p-3 bg-light-warning">
                                                        <h3 class="text-warning mb-1">{{ $attendanceSummary->leave_days ?? 0 }}</h3>
                                                        <p class="mb-0">Leave Days</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Attendance Chart -->
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div id="attendance-chart" style="height: 300px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activities -->
                            @if($employee->attendances && $employee->attendances->count() > 0)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                <i data-feather="activity" class="me-1"></i>
                                                Recent Attendance Records
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                            <th>Working Hours</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($employee->attendances->take(10) as $attendance)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                                            <td>
                                                                @if($attendance->status === 'present')
                                                                    <span class="badge bg-success">Present</span>
                                                                @elseif($attendance->status === 'absent')
                                                                    <span class="badge bg-danger">Absent</span>
                                                                @else
                                                                    <span class="badge bg-warning">Leave</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $attendance->check_in_time ?? 'N/A' }}</td>
                                                            <td>{{ $attendance->check_out_time ?? 'N/A' }}</td>
                                                            <td>
                                                                @if($attendance->check_in_time && $attendance->check_out_time)
                                                                    @php
                                                                        $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                                        $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                                                        $hours = $checkIn->diffInHours($checkOut);
                                                                    @endphp
                                                                    {{ $hours }} hours
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
function exportToPDF() {
    // Implementation for PDF export
    alert('PDF export functionality will be implemented here');
}

// Attendance chart
$(document).ready(function() {
    const attendanceData = {
        present: {{ $attendanceSummary->present_days ?? 0 }},
        absent: {{ $attendanceSummary->absent_days ?? 0 }},
        leave: {{ $attendanceSummary->leave_days ?? 0 }}
    };

    if (typeof ApexCharts !== 'undefined') {
        const attendanceChart = new ApexCharts(document.querySelector("#attendance-chart"), {
            chart: {
                type: 'donut',
                height: 300
            },
            series: [attendanceData.present, attendanceData.absent, attendanceData.leave],
            labels: ['Present', 'Absent', 'Leave'],
            colors: ['#28C76F', '#EA5455', '#FF9F43'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%'
                    }
                }
            }
        });
        attendanceChart.render();
    }
});

// Filter functionality
$('#filterBtn').on('click', function() {
    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();
    let employeeId = '{{ $employee->id }}';

    window.location.href = `/reports/${employeeId}?start_date=${startDate}&end_date=${endDate}`;
});
</script>
@endpush
