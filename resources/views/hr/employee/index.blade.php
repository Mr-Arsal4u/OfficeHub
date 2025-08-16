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
                        <div class="card-header d-inline-flex mt-50">
                            <h4 class="card-title">Employees</h4>
                            <button class="dt-button add-new btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#modals-slide-in" onclick="clearModalForm()">Add New Employee</button>
                        </div>
                        <div class="card-body "></div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Salary</th>
                                        <th>Hire Date</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->first_name ?? 'N/A' }}</td>
                                            <td>{{ $employee->last_name ?? 'N/A' }}</td>
                                            <td>{{ $employee->email ?? 'N/A' }}</td>
                                            <td>{{ $employee->phone ?? 'N/A' }}</td>
                                            <td>{{ $employee->position ?? 'N/A' }}</td>
                                            <td>{{ $employee->department ?? 'N/A' }}</td>
                                            <td>{{ $employee->salary?->amount ?? 'N/A' }}</td>
                                            <td>{{ $employee->hire_date ?? 'N/A' }}</td>
                                            <td>{{ optional($employee->user)->name ?? 'No User' }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light"
                                                        data-bs-toggle="dropdown">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-more-vertical">
                                                            <circle cx="12" cy="12" r="1"></circle>
                                                            <circle cx="12" cy="5" r="1"></circle>
                                                            <circle cx="12" cy="19" r="1"></circle>
                                                        </svg>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)"
                                                            data-bs-toggle="modal" data-bs-target="#modals-slide-in"
                                                            onclick="editEmployee({{ $employee }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-edit-2 me-50">
                                                                <path
                                                                    d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                </path>
                                                            </svg>
                                                            <span>Edit</span>
                                                        </a>
                                                        <form action="{{ route('employee.delete', $employee->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item" type="submit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-trash me-50">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path
                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                    </path>
                                                                </svg>
                                                                <span>Delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
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
    </div>

    <!-- Modal for Adding and Editing Employee -->
    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <form id="employee-form" method="POST" class="add-new-employee modal-content pt-0" novalidate="novalidate">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <!-- First Name -->
                    <div class="mb-1">
                        <label class="form-label" for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-1">
                        <label class="form-label" for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-1">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-1">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>

                    <!-- Position -->
                    <div class="mb-1">
                        <label class="form-label" for="position">Position</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                    </div>

                    <!-- Department -->
                    <div class="mb-1">
                        <label class="form-label" for="department">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>

                    <!-- Hire Date -->
                    <div class="mb-1">
                        <label class="form-label" for="hire_date">Hire Date</label>
                        <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                    </div>

                    <!-- User -->
                    <div class="mb-1">
                        <label class="form-label" for="user_id">Assign Role</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="" selected disabled>Select Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
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
        <script src="{{ asset('files/js/employee.js') }}"></script>
    @endpush
@endsection
