@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{ __('user_profile.title') }}</h1>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                {!! Form::model(auth()->user(), [
                    'method' => 'PUT',
                    'route' => ['userprofile.update', 0]
                ]) !!}

                <div class="form-group mb-3">
                    <label for="name">{{ __('user_profile.username') }}</label>
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                </div>

                <div class="form-group mb-3">
                    <label for="email">{{ __('user_profile.email') }}</label>
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    <!-- Display email validation error -->
                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                </div>


                <div class="form-group mb-3">
                    <label for="password">{{ __('user_profile.password') }}</label>
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('password') !!}</span>
                </div>

                <div class="form-group mb-3">
                    <label for="password_confirmation">{{ __('user_profile.confirm_password') }}</label>
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('password_confirmation') !!}</span>
                </div>

                {!! Form::submit(__('user_profile.save'), ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
