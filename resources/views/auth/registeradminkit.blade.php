<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('adminkit') }}/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* Add background image to the body */
        body {
            position: relative;
            min-height: 100vh; /* Ensure the body takes at least the full viewport height */
            background: none; /* No default background to prevent conflict */
            margin: 0;
            padding: 0;
        }

        body::before {
            content: "";
            position: fixed; /* Ensure the background stays fixed even when scrolling */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('images/mosque.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.9; /* Adjust transparency here */
            z-index: -1; /* Ensure it's behind the content */
        }
    </style>
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">{{ __('register.register') }}</h1>
                            <p class="lead">
                                {{ __('register.empower_msg') }}
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="name" class="form-label">{{ __('register.username') }}</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">{{ __('register.email') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">{{ __('register.password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password-confirm" class="form-label">{{ __('register.confirm_password') }}</label>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                        </div>

                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">
                                                {{ __('register.register_btn') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="text-center text-black mb-3">
                            {{ __('register.already_have_account') }}
                            <a style="color: darkblue;" href="{{ route('login') }}">{{ __('register.log_in') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('adminkit') }}/js/app.js"></script>

</body>

</html>
