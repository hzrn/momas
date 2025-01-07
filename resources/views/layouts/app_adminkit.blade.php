<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin & Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>{{ $title ?? '' }} :: Momas</title>

    <link rel="shortcut icon" href="{{ asset('images/mosque-icon.png') }}" type="image/png" />

    <!-- Preload critical CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" media="print" onload="this.media='all'">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Preload critical JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js" defer></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" media="print" onload="this.media='all'">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js" defer></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" defer></script>

    <style>
        .dropdown-item:focus,
        .dropdown-item:active {
            background-color: transparent;
            color: inherit;
        }
    </style>

    <style>
        .btn-group .btn {
            font-weight: 500;
            border-radius: 20px;
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

        /* Fade-out effect for flash messages */
        .fade-out {
            opacity: 0;
            transition: opacity 1s ease-out;
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
                    <!-- Sidebar navigation here -->
                    <li class="sidebar-item {{Route::is('home') ? 'active' : ''}}">
                        <a class="sidebar-link" href="{{route('home')}}">
                            <i class="align-middle" data-feather="home"></i>
                            <span class="align-middle">{{ __('nav.dashboard') }}</span>
                        </a>
                    </li>
                    <!-- Add more sidebar items as needed -->
                </ul>
            </div>
        </nav>

        <div class="main">
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
                                <a href="{{ url('lang/en') }}" class="btn btn-outline-primary {{ app()->getLocale() === 'en' ? 'active' : '' }}">ENG</a>
                                <a href="{{ url('lang/ms') }}" class="btn btn-outline-primary {{ app()->getLocale() === 'ms' ? 'active' : '' }}">BM</a>
                            </div>
                        </li>

                        <!-- Reminder Icon -->
                        <li class="nav-item my-auto ms-4 dropdown">
                            @if(session('reminders') && count(session('reminders')) > 0)
                                <span class="badge bg-danger rounded-circle">{{ count(session('reminders')) }}</span>
                            @endif
                            <a href="#" class="notification-icon dropdown-toggle" id="reminderDropdown" role="button" data-bs-toggle="dropdown">
                                <i data-feather="bell"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (session('reminders') && count(session('reminders')) > 0)
                                    @foreach(session('reminders') as $reminder)
                                        <li class="dropdown-item">{{ $reminder }}</li>
                                    @endforeach
                                    <li class="dropdown-item text-center">
                                        <button class="btn btn-danger" onclick="removeAllReminders()">{{ __('nav.remove_all_reminders') }}</button>
                                    </li>
                                @else
                                    <li class="dropdown-item">{{ __('nav.no_reminders_for_today') }}</li>
                                @endif
                            </ul>
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
    </div>

    <script>
        // Initialize Feather icons
        document.addEventListener('DOMContentLoaded', function () {
            feather.replace();
        });

        // Clear flash message after a set timeout with fade-out effect
        window.addEventListener('load', function () {
            setTimeout(function () {
                const flashMessage = document.querySelector('.alert');
                if (flashMessage) {
                    flashMessage.classList.add('fade-out');
                    setTimeout(function () {
                        flashMessage.style.display = 'none';
                    }, 1000);
                }
            }, 3000);
        });

        // Remove all reminders
        function removeAllReminders() {
            fetch('/reminders/remove-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dropdownMenu = document.querySelector('#reminderDropdown + .dropdown-menu');
                    dropdownMenu.innerHTML = '<li class="dropdown-item">No reminders for today.</li>';
                    document.querySelector('.badge').textContent = '0';
                    document.querySelector('.badge').style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>
