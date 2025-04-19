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
                            <h4 class="card-title">Expense Records</h4>
                            <button class="dt-button add-new btn btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#expense-modal" onclick="clearModalForm()">Add Expense Record</button>
                        </div>
                        <div class="card-body"></div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Expense Type</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->category ?? 'N/A' }}</td>
                                            <td>{{ $expense->date }}</td>
                                            <td>{{ $expense->amount }}</td>
                                            <td>{{ $expense->description ?? 'N/A' }}</td>
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
                                                            data-bs-toggle="modal" data-bs-target="#expense-modal"
                                                            onclick="editExpense({{ $expense }})">
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
                                                        <form action="{{ route('expense.delete', $expense->id) }}"
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
                                            <td colspan="5" class="text-center">No expense records found.</td>
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

    <!-- Modal for Adding and Editing Expense -->
    <div class="modal modal-slide-in new-expense-modal fade" id="expense-modal" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog">
            <form id="expense-form" method="POST" class="add-new-expense modal-content pt-0" novalidate="novalidate">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Add Expense Record</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="type">Expense Type</label>
                    <select class="form-select" id="category" name="category" required>
                            <option value="" disabled selected>Select Expense Type</option>
                            @foreach ($expenseCategories as $category)
                                <option value="{{ $category->value }}">{{ $category->label() }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('files/js/expense.js') }}"></script>
    @endpush
@endsection
