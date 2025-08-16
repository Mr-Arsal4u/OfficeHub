@extends('layouts.app')

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce">
                <div class="row match-height">
                    <!-- Welcome Card -->
                    <div class="col-xl-4 col-md-6 col-12">
                        <div class="card card-congratulation-medal">
                            <div class="card-body">
                                <h5>Welcome back! 🎉 {{ Auth::user()?->name }}</h5>
                                <p class="card-text font-small-3">Here's what's happening today</p>
                                <h3 class="mb-75 mt-2 pt-50">
                                    <a href="#">{{ $presentToday ?? 0 }} Present Today</a>
                                </h3>
                                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('attendance.index') }}'">View Attendance</button>
                            </div>
                        </div>
                    </div>
                    <!--/ Welcome Card -->

                    <!-- Statistics Card -->
                    <div class="col-xl-8 col-md-6 col-12">
                        <div class="card card-statistics">
                            <div class="card-header">
                                <h4 class="card-title">Today's Overview</h4>
                                <div class="d-flex align-items-center">
                                    <p class="card-text font-small-2 me-25 mb-0">Updated {{ Carbon\Carbon::now()->format('M d, Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="users" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $totalEmployees ?? 0 }}</h4>
                                                <p class="card-text font-small-3 mb-0">Total Employees</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-success me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="check-circle" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $presentToday ?? 0 }}</h4>
                                                <p class="card-text font-small-3 mb-0">Present Today</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-danger me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="x-circle" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $absentToday ?? 0 }}</h4>
                                                <p class="card-text font-small-3 mb-0">Absent Today</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-sm-6 col-12">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-warning me-2">
                                                <div class="avatar-content">
                                                    <i data-feather="calendar" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <h4 class="fw-bolder mb-0">{{ $leaveToday ?? 0 }}</h4>
                                                <p class="card-text font-small-3 mb-0">On Leave</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Card -->
                </div>

                <div class="row match-height">
                    <div class="col-lg-4 col-12">
                        <div class="row match-height">
                            <!-- Salary Overview -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <div class="card">
                                    <div class="card-body pb-50">
                                        <h6>This Month's Salary</h6>
                                        <h2 class="fw-bolder mb-1">${{ number_format($totalSalaryThisMonth ?? 0, 0) }}</h2>
                                        <p class="card-text text-muted">
                                            @if(isset($salaryGrowth))
                                                @if($salaryGrowth > 0)
                                                    <span class="text-success">+{{ number_format($salaryGrowth, 1) }}%</span>
                                                @else
                                                    <span class="text-danger">{{ number_format($salaryGrowth, 1) }}%</span>
                                                @endif
                                                from last month
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/ Salary Overview -->

                            <!-- Expenses Overview -->
                            <div class="col-lg-6 col-md-3 col-6">
                                <div class="card card-tiny-line-stats">
                                    <div class="card-body pb-50">
                                        <h6>This Month's Expenses</h6>
                                        <h2 class="fw-bolder mb-1">${{ number_format($totalExpensesThisMonth ?? 0, 0) }}</h2>
                                        <p class="card-text text-muted">
                                            @if(isset($expenseGrowth))
                                                @if($expenseGrowth > 0)
                                                    <span class="text-danger">+{{ number_format($expenseGrowth, 1) }}%</span>
                                                @else
                                                    <span class="text-success">{{ number_format($expenseGrowth, 1) }}%</span>
                                                @endif
                                                from last month
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/ Expenses Overview -->

                            <!-- Monthly Summary Card -->
                            <div class="col-lg-12 col-md-6 col-12">
                                <div class="card earnings-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="card-title mb-1">Monthly Summary</h4>
                                                <div class="font-small-2">Current Month</div>
                                                <h5 class="mb-1">${{ number_format(($totalSalaryThisMonth ?? 0) - ($totalExpensesThisMonth ?? 0), 0) }}</h5>
                                                <p class="card-text text-muted font-small-2">
                                                    Net: Salary - Expenses
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex flex-column">
                                                    <div class="mb-2">
                                                        <span class="text-success">●</span> Salary: ${{ number_format($totalSalaryThisMonth ?? 0, 0) }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-danger">●</span> Expenses: ${{ number_format($totalExpensesThisMonth ?? 0, 0) }}
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-info">●</span> Loans: ${{ number_format($totalLoanAmount ?? 0, 0) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Monthly Summary Card -->
                        </div>
                    </div>

                    <!-- Revenue Report Card -->
                    <div class="col-lg-8 col-12">
                        <div class="card card-revenue-budget">
                            <div class="row mx-0">
                                <div class="col-md-8 col-12 revenue-report-wrapper">
                                    <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                        <h4 class="card-title mb-50 mb-sm-0">Monthly Trends</h4>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center me-2">
                                                <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                                <span>Salary</span>
                                            </div>
                                            <div class="d-flex align-items-center ms-75">
                                                <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                                <span>Expenses</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="revenue-report-chart"></div>
                                </div>
                                <div class="col-md-4 col-12 budget-wrapper">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ Carbon\Carbon::now()->format('Y') }}
                                        </button>
                                        <div class="dropdown-menu">
                                            @for($year = Carbon\Carbon::now()->year; $year >= Carbon\Carbon::now()->year - 2; $year--)
                                                <a class="dropdown-item" href="#">{{ $year }}</a>
                                            @endfor
                                        </div>
                                    </div>
                                    <h2 class="mb-25">${{ number_format($totalSalaryThisMonth ?? 0, 0) }}</h2>
                                    <div class="d-flex justify-content-center">
                                        <span class="fw-bolder me-25">Pending Salaries:</span>
                                        <span>{{ $pendingSalaries ?? 0 }}</span>
                                    </div>
                                    <div id="budget-chart"></div>
                                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('salary.index') }}'">View Salaries</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Revenue Report Card -->
                </div>

                <div class="row match-height">
                    <!-- Recent Activities Table -->
                    <div class="col-lg-8 col-12">
                        <div class="card card-company-table">
                            <div class="card-header">
                                <h4 class="card-title">Recent Activities</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Activity</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentAttendances as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar rounded">
                                                            <div class="avatar-content">
                                                                <i data-feather="user" class="font-medium-3"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bolder">{{ $attendance->employee->first_name ?? 'N/A' }} {{ $attendance->employee->last_name ?? 'N/A' }}</div>
                                                            <div class="font-small-2 text-muted">{{ $attendance->employee->email ?? 'N/A' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar bg-light-primary me-1">
                                                            <div class="avatar-content">
                                                                <i data-feather="calendar" class="font-medium-3"></i>
                                                            </div>
                                                        </div>
                                                        <span>Attendance</span>
                                                    </div>
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bolder mb-25">{{ Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</span>
                                                        <span class="font-small-2 text-muted">{{ $attendance->check_in_time ?? 'N/A' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($attendance->status === 'present')
                                                        <span class="badge bg-success">Present</span>
                                                    @elseif($attendance->status === 'absent')
                                                        <span class="badge bg-danger">Absent</span>
                                                    @else
                                                        <span class="badge bg-warning">Leave</span>
                                                    @endif
                                                </td>
                                                <td>-</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No recent activities</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Recent Activities Table -->

                    <!-- Quick Stats Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-developer-meetup">
                            <div class="meetup-img-wrapper rounded-top text-center">
                                <img src="{{ asset('admin-panel/app-assets/images/illustration/email.svg') }}" alt="Stats Pic" height="170" />
                            </div>
                            <div class="card-body">
                                <div class="meetup-header d-flex align-items-center">
                                    <div class="meetup-day">
                                        <h6 class="mb-0">{{ Carbon\Carbon::now()->format('M') }}</h6>
                                        <h3 class="mb-0">{{ Carbon\Carbon::now()->format('d') }}</h3>
                                    </div>
                                    <div class="my-auto">
                                        <h4 class="card-title mb-25">Quick Stats</h4>
                                        <p class="card-text mb-0">Current month overview</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Pending Salaries:</span>
                                        <span class="fw-bolder">{{ $pendingSalaries ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Approved Loans:</span>
                                        <span class="fw-bolder">{{ $approvedLoans ?? 0 }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Total Expenses:</span>
                                        <span class="fw-bolder">${{ number_format($totalExpensesThisMonth ?? 0, 0) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Active Employees:</span>
                                        <span class="fw-bolder">{{ $activeEmployees ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Quick Stats Card -->

                    <!-- Department Distribution -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-browser-states">
                            <div class="card-header">
                                <div>
                                    <h4 class="card-title">Department Distribution</h4>
                                    <p class="card-text font-small-2">Employee count by department</p>
                                </div>
                            </div>
                            <div class="card-body">
                                @forelse($departmentStats as $dept)
                                <div class="browser-states">
                                    <div class="d-flex">
                                        <div class="avatar bg-light-primary rounded me-1">
                                            <div class="avatar-content">
                                                <i data-feather="users" class="font-medium-3"></i>
                                            </div>
                                        </div>
                                        <h6 class="align-self-center mb-0">{{ $dept->department ?? 'Not Assigned' }}</h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="fw-bold text-body-heading me-1">{{ $dept->count }}</div>
                                        <div class="progress" style="width: 100px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($dept->count / $totalEmployees) * 100 }}%" aria-valuenow="{{ $dept->count }}" aria-valuemin="0" aria-valuemax="{{ $totalEmployees }}"></div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted">No department data available</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <!--/ Department Distribution -->

                    <!-- Top Employees -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Top Employees</h4>
                                <i data-feather="award" class="font-medium-3 text-muted cursor-pointer"></i>
                            </div>
                            <div class="card-body p-0">
                                <div class="row border-top text-center mx-0">
                                    @forelse($topEmployees as $employee)
                                    <div class="col-12 border-bottom py-1">
                                        <div class="d-flex justify-content-between align-items-center px-2">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary rounded me-1">
                                                    <div class="avatar-content">
                                                        <i data-feather="user" class="font-medium-3"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="card-text mb-0">{{ $employee->first_name ?? 'N/A' }} {{ $employee->last_name ?? 'N/A' }}</p>
                                                    <small class="text-muted">{{ $employee->attendances_count ?? 0 }} days present</small>
                                                </div>
                                            </div>
                                            <span class="badge bg-success">{{ $employee->attendances_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 py-2 text-center text-muted">No data available</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Top Employees -->
                </div>
            </section>
            <!-- Dashboard Ecommerce ends -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart data from PHP
const monthlyData = @json($monthlyData ?? []);

// Initialize charts when document is ready
$(document).ready(function() {
    // Revenue Report Chart
    if (typeof ApexCharts !== 'undefined' && monthlyData.months) {
        const revenueChart = new ApexCharts(document.querySelector("#revenue-report-chart"), {
            chart: {
                height: 240,
                type: 'area',
                toolbar: { show: false }
            },
            series: [
                {
                    name: 'Salary',
                    data: monthlyData.salary || []
                },
                {
                    name: 'Expenses',
                    data: monthlyData.expense || []
                }
            ],
            xaxis: {
                categories: monthlyData.months || []
            },
            colors: ['#7367F0', '#FF9F43'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            legend: {
                show: false
            }
        });
        revenueChart.render();
    }
});
</script>
@endpush