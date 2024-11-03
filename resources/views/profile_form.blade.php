@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{ $title }}</h1>

            {!! Form::model($profile, [
                'route' => $route,
                'method' => $method,
            ]) !!}

            <div class="form-group">
                {!! Form::label('category', __('profile.category')) !!}
                {!! Form::select('category', [
                    'Mission' => __('profile.mission'),
                    'Vision' => __('profile.vision'),
                    'Objective' => __('profile.objective'),
                    'History' => __('profile.history')
                ], null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('category') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('title', __('profile.title')) !!} <!-- Fixed here -->
                {!! Form::text('title', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('title') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('content', __('profile.content')) !!} <!-- Fixed here -->
                {!! Form::textarea('content', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            {!! Form::submit(__('profile.save'), ['class' => 'btn btn-success']) !!} <!-- Fixed here -->

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
