<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin & Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

    <title>{{ $title ?? '' }} :: Momas</title>

    <link rel="shortcut icon" href="{{ asset('images/mosque-icon.png') }}" type="image/png" />

    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">




    <style>
        .dropdown-item:focus,
        .dropdown-item:active {
            background-color: transparent; /* Remove background color on focus/active */
            color: inherit; /* Keep the text color as it is */
        }
    </style>


</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="#">
                    <span class="align-middle">{{ __('nav.main') }}</span>
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-item {{Route::is('home') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('home')}}">
                            <i class="align-middle" data-feather="home"></i>
                            <span class="align-middle">{{ __('nav.dashboard') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('mosque.*') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('mosque.create')}}">
                            <i class="align-middle" data-feather="sliders"></i>
                            <span class="align-middle">{{ __('mosque.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('profile.*') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('profile.index')}}">
                            <i class="align-middle" data-feather="list"></i>
                            <span class="align-middle">{{ __('profile.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('committee.*') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('committee.index')}}">
                            <i class="align-middle" data-feather="users"></i>
                            <span class="align-middle">{{ __('committee.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Route::is('cashflow.*') && !Route::is('cashflow.analysis') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('cashflow.index') }}">
                            <i class="align-middle" data-feather="dollar-sign"></i>
                            <span class="align-middle">{{ __('cashflow.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('categoryinfo.*') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('categoryinfo.index')}}">
                            <i class="align-middle" data-feather="more-vertical"></i>
                            <span class="align-middle">{{ __('categoryinfo.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('info.*') && !Route::is('info.analysis') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{route('info.index')}}">
                            <i class="align-middle" data-feather="info"></i>
                            <span class="align-middle">{{ __('info.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('categoryitem.*') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('categoryitem.index')}}">
                            <i class="align-middle" data-feather="more-vertical"></i>
                            <span class="align-middle">{{ __('categoryitem.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{Route::is('item.*') && !Route::is('item.analysis') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{route('item.index')}}">
                            <i class="align-middle" data-feather="package"></i>
                            <span class="align-middle">{{ __('item.title') }}</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Route::is('analysis.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="#analysis" data-bs-toggle="collapse" aria-expanded="false" aria-controls="analysis">
                            <i class="align-middle" data-feather="bar-chart-2"></i>
                            <span class="align-middle">{{ __('nav.analysis') }}</span>
                        </a>
                        <ul class="sidebar-dropdown collapse show" id="analysis">
                            <li style="list-style: initial" class="sidebar-item {{ Route::is('cashflow.analysis') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('cashflow.analysis') }}">
                                    <i class="align-middle" data-feather="dollar-sign"></i>
                                    <span class="align-middle">{{ __('nav.cashflow_analysis') }}</span>
                                </a>
                            </li>
                            <li style="list-style: initial" class="sidebar-item {{ Route::is('info.analysis') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('info.analysis') }}">
                                    <i class="align-middle" data-feather="info"></i>
                                    <span class="align-middle">{{ __('nav.info_analysis') }}</span>
                                </a>
                            </li>
                            <li style="list-style: initial" class="sidebar-item {{ Route::is('item.analysis') ? 'active' : '' }}">
                                <a class="sidebar-link" href="{{ route('item.analysis') }}">
                                    <i class="align-middle" data-feather="package"></i>
                                    <span class="align-middle">{{ __('nav.item_analysis') }}</span>
                                </a>
                            </li>
                            <!-- Add more dropdown items here if needed -->
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="main" style="max-height: 100vh; overflow-y: auto;">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <div class="fw-bold">
                                {{ optional(auth()->user()->mosque)->name ?? __('mosque.no_mosque') }}
                            </div>
                        </li>
                    </ul>

                    <ul class="navbar-nav navbar-align ms-auto">
                        <!-- Language Switcher -->
                        <li class="nav-item my-auto">
                            <div class="btn-group" role="group" aria-label="Language Switcher">
                                <a href="{{ url('lang/en') }}" class="btn btn-outline-primary {{ app()->getLocale() === 'en' ? 'active' : '' }}" style="min-width: 50px; padding: 5px 10px;">ENG</a>
                                <a href="{{ url('lang/ms') }}" class="btn btn-outline-primary {{ app()->getLocale() === 'ms' ? 'active' : '' }}" style="min-width: 50px; padding: 5px 10px;">BM</a>
                            </div>
                        </li>

                        <!-- Reminder Icon -->
                        <li class="nav-item my-auto ms-4 dropdown" style="position: relative;">
                            @if(session('reminders') && count(session('reminders')) > 0)
                                <span class="badge bg-danger rounded-circle" style="position: absolute; left: -12px; top: -5px; font-size: 0.75rem;">{{ count(session('reminders')) }}</span>
                            @endif
                            <a href="#" class="notification-icon dropdown-toggle" id="reminderDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i data-feather="bell"></i> <!-- Feather icon -->
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="reminderDropdown">
                                @if (session('reminders') && count(session('reminders')) > 0)
                                    @foreach(session('reminders') as $reminder)
                                        <li class="dropdown-item">{{ $reminder }}</li>
                                        @if (!$loop->last) <!-- Add divider except for the last item -->
                                            <li><hr class="dropdown-divider"></li>
                                        @endif
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li class="dropdown-item text-center">
                                        <button class="btn btn-danger" onclick="removeAllReminders()">{{ __('nav.remove_all_reminders') }}</button>
                                    </li>
                                @else
                                    <li class="dropdown-item">{{ __('nav.no_reminders_for_today') }}</li>
                                @endif
                            </ul>
                        </li>

                        <!-- Avatar Section -->
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <img src="{{ asset('images/avatar.png') }}" alt="{{ auth()->user()->name }}" class="avatar img-fluid rounded me-1" style="height: 30px; width: 30px;" />
                                <span class="text-dark">{{ auth()->user()->name }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('userprofile.edit', 0) }}">
                                    <i class="align-middle me-1" data-feather="user"></i> {{ __('nav.profile') }}
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout-user') }}">{{ __('nav.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @include('flash::message')
                    @yield('content')
                </div>
            </main>

        </div>

        <!-- Optional Custom CSS -->
        <style>
            .btn-group .btn {
                font-weight: 500;
                border-radius: 20px; /* Rounded buttons */
                transition: all 0.3s ease;
            }

            .btn-group .btn:hover {
                background-color: #007bff;
                color: white;
            }

            .btn-group .active {
                background-color: #007bff;
                color: white;
            }
        </style>
    </div>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>

    <script>
        // Auto-hide flash messages after 3 seconds
        $(document).ready(function() {
            $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
        });
    </script>

    <script>
        // Initialize Feather icons
        feather.replace();

        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('#sidebar');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed'); // Add or remove the 'collapsed' class
            });
        });

    </script>


    <script>
        function removeAllReminders() {
            fetch('/reminders/remove-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear the reminders from the dropdown
                    const dropdownMenu = document.querySelector('#reminderDropdown + .dropdown-menu');
                    dropdownMenu.innerHTML = '<li class="dropdown-item">No reminders for today.</li>'; // Update dropdown content
                    document.querySelector('.badge').textContent = '0'; // Update badge count
                    document.querySelector('.badge').style.display = 'none'; // Hide badge if count is 0
                } else {
                    console.error('Failed to remove all reminders');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get all sidebar items that have a dropdown
            const sidebarItems = document.querySelectorAll('.sidebar-item');

            sidebarItems.forEach(item => {
                const link = item.querySelector('.sidebar-link[data-bs-toggle="collapse"]');
                if (link) {
                    link.addEventListener('click', function (event) {
                        const dropdownId = this.getAttribute('href');
                        const dropdown = document.querySelector(dropdownId);

                        // If the dropdown is already open, prevent it from closing
                        if (dropdown.classList.contains('show')) {
                            event.stopPropagation(); // Prevent the event from bubbling up
                        }
                    });
                }
            });

            // Add event listener to dropdown items to prevent collapse
            const dropdownItems = document.querySelectorAll('.sidebar-dropdown .sidebar-link');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function (event) {
                    event.stopPropagation(); // Prevent the dropdown from collapsing
                });
            });
        });
    </script>

</body>
</html>
