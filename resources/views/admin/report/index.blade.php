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
                            <h2 class="content-header-title float-start mb-0">Employee Reports</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Reports</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center mt-50">
                            <h4 class="card-title">Employee Reports</h4>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel me-1"></i> Export Excel
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                                </button>
                            </div>
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
                                                        <i data-feather="users" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ $employees->count() }}</h4>
                                                    <small class="text-muted">Total Employees</small>
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
                                                    <h4 class="mb-0">{{ $employees->where('department', '!=', null)->count() }}</h4>
                                                    <small class="text-muted">Assigned Departments</small>
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
                                                        <i data-feather="calendar" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ \App\Models\Attendance::where('date', \Carbon\Carbon::today())->count() }}</h4>
                                                    <small class="text-muted">Today's Attendance</small>
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
                                                        <i data-feather="dollar-sign" class="text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 class="mb-0">{{ \App\Models\Salary::where('status', 'pending')->count() }}</h4>
                                                    <small class="text-muted">Pending Salaries</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped" id="employees-table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employees as $employee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar rounded me-2">
                                                        <div class="avatar-content">
                                                            <i data-feather="user" class="font-medium-3"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bolder">{{ $employee->first_name ?? 'N/A' }} {{ $employee->last_name ?? 'N/A' }}</div>
                                                        <small class="text-muted">ID: {{ $employee->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($employee->department)
                                                    <span class="badge bg-light-primary">{{ $employee->department }}</span>
                                                @else
                                                    <span class="badge bg-light-secondary">Not Assigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $employee->position ?? 'N/A' }}</td>
                                            <td>{{ $employee->email ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $todayAttendance = $employee->attendances->where('date', \Carbon\Carbon::today())->first();
                                                @endphp
                                                @if($todayAttendance)
                                                    @if($todayAttendance->status === 'present')
                                                        <span class="badge bg-success">Present</span>
                                                    @elseif($todayAttendance->status === 'absent')
                                                        <span class="badge bg-danger">Absent</span>
                                                    @else
                                                        <span class="badge bg-warning">Leave</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-light-secondary">No Record</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical" class="font-medium-3"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a href="{{ route('reports.show', $employee->id) }}" class="dropdown-item">
                                                            <i data-feather="eye" class="me-50"></i>
                                                            <span>View Report</span>
                                                        </a>
                                                        <a href="{{ route('reports.show', $employee->id) }}?type=attendance" class="dropdown-item">
                                                            <i data-feather="calendar" class="me-50"></i>
                                                            <span>Attendance Report</span>
                                                        </a>
                                                        <a href="{{ route('reports.show', $employee->id) }}?type=salary" class="dropdown-item">
                                                            <i data-feather="dollar-sign" class="me-50"></i>
                                                            <span>Salary Report</span>
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a href="{{ route('employees.edit', $employee->id) }}" class="dropdown-item">
                                                            <i data-feather="edit" class="me-50"></i>
                                                            <span>Edit Employee</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No employees found.</td>
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

// Initialize DataTable for better table functionality
$(document).ready(function() {
    $('#employees-table').DataTable({
        pageLength: 25,
        order: [[0, 'asc']],
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
@endpush
