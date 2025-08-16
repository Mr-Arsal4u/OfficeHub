@extends('layouts.app')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row"></div>
            <div class="row" id="table-striped">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between mt-50">
                            <h4 class="card-title mb-2">Attendance Records</h4>

                            <div class="d-flex flex-wrap gap-2">
                                <!-- Filter Buttons -->
                                <button class="btn btn-success" onclick="setStatus('present')">
                                    All Present
                                </button>

                                <button class="btn btn-danger" onclick="setStatus('absent')">
                                    All Absent
                                </button>

                                <!-- Date Filter -->
                                <input type="date" class="form-control" id="attendance-date-filter">
                            </div>
                        </div>

                        <div class="card-body"></div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
                                    </tr>
                                </thead>
                                <tbody id="attendance-table">
                                    @include('hr.attendance.attendance_table', ['employees' => $employees])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding and Editing Attendance -->
    <div class="modal modal-slide-in new-attendance-modal fade" id="attendance-modal" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <form id="attendance-form" method="POST" class="add-new-attendance modal-content pt-0" novalidate="novalidate">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Add Attendance Record</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <!-- Employee -->
                    <div class="mb-1">
                        <label class="form-label" for="employee_id">Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="" selected disabled>Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div class="mb-1">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-1">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>

                    <div class="mb-1 check_in">
                        <label class="form-label" for="check_in_time">Check In Time</label>
                        <input type="time" class="form-control" id="check_in_time" name="check_in_time" required>
                    </div>

                    <div class="mb-1 check_out">
                        <label class="form-label" for="check_out_time">Check Out Time</label>
                        <input type="time" class="form-control" id="check_out_time" name="check_out_time" required>
                    </div>

                    <button type="submit"
                        class="btn btn-primary me-1 data-submit waves-effect waves-float waves-light">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary waves-effect"
                        data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('files/js/attendance.js') }}"></script>
    @endpush
@endsection
