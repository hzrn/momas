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
	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('adminkit/css/app.css')}}" rel="stylesheet">
	
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
							<i class="align-middle" data-feather="sliders">
								</i> <span class="align-middle">Dashboard</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('mosque.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('mosque.create')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Mosque Data</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('profile.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('profile.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Mosque Profile</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('committee.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('committee.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Committee</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('cashflow.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('cashflow.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Cashflow</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('categoryinfo.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('categoryinfo.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Category Info</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('info.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('info.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Info</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('categoryitem.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('categoryitem.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Category Item</span>
						</a>
					</li>

					<li class="sidebar-item {{Route::is('item.*') ? 'active' : ''}}">
						<a class="sidebar-link" href="{{route('item.index')}}">
							<i class="align-middle" data-feather="user">
								</i> <span class="align-middle">Item</span>
						</a>
					</li>

					

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
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<img src="{{asset('images/avatar.png')}}" class="avatar img-fluid rounded me-1" alt="{{auth()->user()->name}}" /> <span class="text-dark">{{auth()->user()->name}}</span>
							</a>

							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{route('userprofile.edit',0)}}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="{{route('logout-user')}}">Log out</a>
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
	</div>

	<script src="{{asset('adminkit/js/app.js')}}"></script>
	@yield('CashflowChartjs')

</body>

</html>