<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <div class="mt-2 mr-2">
                    <h2>Office Nexus</h2>
                </div>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="index.html"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="Dashboards">Dashboards</span><span
                        class="badge badge-light-warning rounded-pill ms-auto me-1">2</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="dashboard-analytics.html"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="Analytics">Analytics</span></a>
                    </li>
                    <li class="active"><a class="d-flex align-items-center" href="dashboard-ecommerce.html"><i
                                data-feather="circle"></i><span class="menu-item text-truncate"
                                data-i18n="eCommerce">eCommerce</span></a>
                    </li>
                </ul>
            </li> --}}

            <li class="nav-item"><a class="d-flex align-items-center" href="{{ route('dashboard') }}"><i
                        data-feather="home"></i><span class="menu-title text-truncate"
                        data-i18n="eCommerce">Dasboard</span></a>
            </li>

            {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="folder"></i><span
                        class="menu-title text-truncate" data-i18n="Documentation">User Management</span></a>
            </li> --}}
            <li class="nav-item has-sub">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="folder"></i>
                    <span class="menu-title text-truncate" data-i18n="UserManagement">User Management</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="d-flex align-items-center" href="{{ route('employees.index') }}">
                            <i data-feather="user"></i>
                            <span class="menu-item text-truncate" data-i18n="Employees">Employees</span>
                        </a>
                    </li>
                    <li>
                        {{-- <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                            <i data-feather="users"></i>
                            <span class="menu-item text-truncate" data-i18n="Users">Users</span>
                        </a> --}}
                    </li>
                    <li>
                        <a class="d-flex align-items-center" href="{{ route('attendance.index') }}">
                            <i data-feather="users"></i>
                            <span class="menu-item text-truncate" data-i18n="Users">Attendance</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('attendance.index') }}"><i
                        data-feather="life-buoy"></i><span class="menu-title text-truncate"
                        data-i18n="Raise Support">Attendance</span></a>
            </li> --}}
            {{-- <li class=" nav-item"><a class="d-flex align-items-center" href="{{ route('employees.index') }}"><i
                        data-feather="life-buoy"></i><span class="menu-title text-truncate"
                        data-i18n="Raise Support">Employee Management</span></a>
            </li> --}}
            <li class="nav-item has-sub" style="">
                <a class="d-flex align-items-center" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-file-text">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="menu-title text-truncate" data-i18n="Manage Finance">Manage Finance</span>
                </a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{ route('expense.index') }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg><span class="menu-item text-truncate" data-i18n="Expenses">Expenses</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ route('salary.index') }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg><span class="menu-item text-truncate" data-i18n="Salaries">Salaries</span></a></li>
                </ul>
            </li>

            <li class="nav-item has-sub" style="">
                <a class="d-flex align-items-center" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-file-text">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="menu-title text-truncate" data-i18n="Manage Finance">Reports</span>
                </a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{ route('reports.index') }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg><span class="menu-item text-truncate" data-i18n="Expenses">Reports</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ route('reports.ai') }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg><span class="menu-item text-truncate" data-i18n="Salaries">Ask Ai</span></a></li>
                </ul>
            </li>

            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i
                        data-feather="life-buoy"></i><span class="menu-title text-truncate"
                        data-i18n="Raise Support">Sales Management</span></a>
            </li>
       
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i
                        data-feather="life-buoy"></i><span class="menu-title text-truncate"
                        data-i18n="Raise Support">Roles and Permissions </span></a>
            </li>
            <li class=" nav-item"><a class="d-flex align-items-center" href="#"><i
                        data-feather="life-buoy"></i><span class="menu-title text-truncate"
                        data-i18n="Raise Support">Settings</span></a>
            </li>
        </ul>
    </div>
</div>
