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
                                        <strong class="me-2">Amount:</strong>
                                        <span>{{ $employee?->salary?->amount ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Date:</strong>
                                        <span>{{ $employee?->salary?->date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Bonus:</strong>
                                        <span>{{ $employee?->salary?->bonus ?? 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <strong class="me-2">Description:</strong>
                                        <span>{{ $employee?->salary?->description ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Section: Attendance Summary --}}
                        <div class="card-header d-inline-flex mt-1">
                            <h4 class="card-title">Attendance Summary
                                ({{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }})</h4>
                        </div>

                        <div class="card-body pt-0">
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Present Days:</strong>
                                        <span>{{ $attendanceSummary->present_days ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Absent Days:</strong>
                                        <span>{{ $attendanceSummary->absent_days ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex">
                                        <strong class="me-2">Leave Days:</strong>
                                        <span>{{ $attendanceSummary->leave_days ?? 0 }}</span>
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
