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
                            <h4 class="card-title">Payment Requests</h4>
                            <button class="dt-button add-new btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#salary-modal" onclick="clearModalForm()">Add Payment request</button>
                        </div>
                        <div class="card-body"></div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Salary Amount</th>
                                        <th>Request Type</th>
                                        <th>Amount Requested</th>
                                        <th>Requested Date</th>
                                        <th>Is Approved</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $request)
                                        <tr>
                                            <td>
                                                {{ $request?->employee?->first_name }}
                                                {{ $request?->employee?->last_name }}
                                            </td>
                                            <td>
                                                {{ $request?->employee?->salary?->amount ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $request?->type?->label() ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $request?->amount ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $request?->created_at->format('d M Y') ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $request?->is_approved->label() ?? 'N/A' }}
                                            </td>
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
                                                            data-bs-toggle="modal" data-bs-target="#salary-modal"
                                                            onclick="editSalary({{ $request }})">
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

                                                        @if (auth()->user() && auth()->user()->hasRole('admin'))
                                                            @if ($request->is_approved?->value == \App\Enum\RequestIsApproved::NO->value)
                                                                <form
                                                                    action="{{ route('request.payment.status.update', $request->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button class="dropdown-item" type="submit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-check-circle me-50">
                                                                            <path d="M9 11l3 3L22 4"></path>
                                                                            <circle cx="12" cy="12" r="10">
                                                                            </circle>
                                                                        </svg>
                                                                        <span>Approve</span>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form
                                                                    action="{{ route('request.payment.status.update', $request->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button class="dropdown-item" type="submit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="feather feather-x-circle me-50">
                                                                            <circle cx="12" cy="12" r="10">
                                                                            </circle>
                                                                            <line x1="15" y1="9"
                                                                                x2="9" y2="15"></line>
                                                                            <line x1="9" y1="9"
                                                                                x2="15" y2="15"></line>
                                                                        </svg>
                                                                        <span>Reject</span>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif

                                                        <form action="{{ route('request.payment.delete', $request->id) }}"
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
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No records found.</td>
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

    <!-- Modal for Adding and Editing Salary -->
    <div class="modal modal-slide-in new-salary-modal fade" id="salary-modal" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <form id="salary-form" method="POST" class="add-new-salary modal-content pt-0" novalidate>
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Add Payment Request</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="employee_id">Employee</label>
                        <select class="form-control" id="employee_id" name="employee_id" required>
                            <option value="" selected disabled>Select Employee</option>
                            @foreach ($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="type">Type</label>
                        {{-- If type has fixed options, replace with a select dropdown like below --}}
                        <select class="form-control" id="type" name="type" required>
                            <option value="" disabled selected>Select Type</option>
                            @foreach ($types as $type)
                                <option value="{{ $type?->value }}">{{ $type?->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('files/js/loan.js') }}"></script>
    @endpush
@endsection
