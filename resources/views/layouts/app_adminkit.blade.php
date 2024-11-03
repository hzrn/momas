<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

    <title>{{ $title ?? '' }} :: Momas</title>

    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="index.html">
          <span class="align-middle">Main Menu</span>
        </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>

                <li class="sidebar-item {{Route::is('home') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('home')}}">
                        <i class="align-middle" data-feather="home">
                            </i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('mosque.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('mosque.create')}}">
                        <i class="align-middle" data-feather="sliders">
                            </i> <span class="align-middle">{{ __('mosque.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('profile.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('profile.index')}}">
                        <i class="align-middle" data-feather="list">
                            </i> <span class="align-middle">{{ __('profile.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('committee.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('committee.index')}}">
                        <i class="align-middle" data-feather="users">
                            </i> <span class="align-middle">{{ __('committee.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('cashflow.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('cashflow.index')}}">
                        <i class="align-middle" data-feather="dollar-sign">
                            </i> <span class="align-middle">{{ __('cashflow.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('categoryinfo.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('categoryinfo.index')}}">
                        <i class="align-middle" data-feather="more-vertical">
                            </i> <span class="align-middle">{{ __('categoryinfo.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('info.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('info.index')}}">
                        <i class="align-middle" data-feather="info">
                            </i> <span class="align-middle">{{ __('info.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('categoryitem.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('categoryitem.index')}}">
                        <i class="align-middle" data-feather="more-vertical">
                            </i> <span class="align-middle">{{ __('categoryitem.title') }}</span>
                    </a>
                </li>

                <li class="sidebar-item {{Route::is('item.*') ? 'active' : ''}}">
                    <a class="sidebar-link" href="{{route('item.index')}}">
                        <i class="align-middle" data-feather="package">
                            </i> <span class="align-middle">{{ __('item.title') }}</span>
                    </a>
                </li>

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
                      {{ optional(auth()->user()->mosque)->name ?? 'No mosque assigned' }}
                    </div>
                  </li>
                </ul>

                <ul class="navbar-nav navbar-align ms-auto">
                  <!-- Language Switcher -->
                  <li class="nav-item my-auto">
                    <div class="btn-group" role="group" aria-label="Language Switcher">
                      <a
                        href="{{ url('lang/en') }}"
                        class="btn btn-outline-primary {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        style="min-width: 50px; padding: 5px 10px;"
                      >
                        ENG
                      </a>
                      <a
                        href="{{ url('lang/ms') }}"
                        class="btn btn-outline-primary {{ app()->getLocale() === 'ms' ? 'active' : '' }}"
                        style="min-width: 50px; padding: 5px 10px;"
                      >
                        BM
                      </a>
                    </div>
                  </li>

                  <!-- Avatar Section -->
                  <li class="nav-item dropdown ms-3">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                      <i class="align-middle" data-feather="settings"></i>
                    </a>

                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img
                          src="{{ asset('images/avatar.png') }}"
                          alt="{{ auth()->user()->name }}"
                          class="avatar img-fluid rounded me-1"
                          style="height: 30px; width: 30px;"
                        />
                        <span class="text-dark">{{ auth()->user()->name }}</span>
                      </a>


                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item" href="{{ route('userprofile.edit', 0) }}">
                        <i class="align-middle me-1" data-feather="user"></i> Profile
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route('logout-user') }}">Log out</a>
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

    <!-- Custom JS -->
    <script src="{{ asset('adminkit/js/app.js') }}"></script>

	@yield('CashflowChartjs')

</body>

</html>
