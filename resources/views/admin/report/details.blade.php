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

                        {{-- Section: Personal Information --}}
                        <div class="card-header d-inline-flex mt-50">
                            <h4 class="card-title">Personal Information</h4>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Email:</strong>
                                        <span>{{ $employee?->email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Phone:</strong>
                                        <span>{{ $employee?->phone ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Position:</strong>
                                        <span>{{ $employee?->position ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Department:</strong>
                                        <span>{{ $employee?->department ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Salary Information --}}
                        <div class="card-header d-inline-flex mt-2">
                            <h4 class="card-title">Salary Information</h4>
                        </div>

                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Per Month:</strong>
                                        <span class="salary-monthly">{{ $monthlySalary ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Per Day:</strong>
                                        <span>{{ $employee?->salary?->amount ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Description:</strong>
                                        <span>{{ $employee?->salary?->description ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Section: Attendance Summary --}}
                        <div class="row mb-3 ml-5" style="margin-left: 20px;" >
                            <div class="col-md-3">
                                <label for="startDate">Start Date</label>
                                <input type="date" id="startDate" class="form-control"
                                    value="{{ request()->start_date ?? \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                            </div>
                            <div class="col-md-3">
                                <label for="endDate">End Date</label>
                                <input type="date" id="endDate" class="form-control"
                                    value="{{ request()->end_date ?? \Carbon\Carbon::now()->endOfMonth()->toDateString() }}">
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button class="btn btn-primary" id="filterBtn">Filter</button>
                            </div>
                        </div>

                        <div class="card-header d-inline-flex mt-1">
                            <h4 class="card-title attendance-card">Attendance Summary
                                ({{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})</h4>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Present Days:</strong>
                                        <span class="present-days">{{ $attendanceSummary->present_days ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Absent Days:</strong>
                                        <span class="absent-days">{{ $attendanceSummary->absent_days ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Leave Days:</strong>
                                        <span class="leave-days">{{ $attendanceSummary->leave_days ?? 0 }}</span>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $('#filterBtn').on('click', function() {
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let employeeId = '{{ $employee->id }}'; // If not available, pass it dynamically

            $.ajax({
                url: '/reports/' + employeeId,
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    console.log(response);
                    $('.attendance-card').text(`Attendance Summary (${response.formatted_range})`);
                    $('.present-days').text(response.present_days);
                    $('.absent-days').text(response.absent_days);
                    $('.leave-days').text(response.leave_days);
                    $(".salary-monthly").text(response.monthlySalary);
                }
            });
        });
    </script>
@endpush
