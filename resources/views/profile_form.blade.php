@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($profile, [
                'route' => $route,
                'method' => $method,
            ]) !!}

            <div class="form-group">
                {!! Form::label('category', 'Category') !!}
                {!! Form::select('category', ['Mission' => 'Mission', 'Vision' => 'Vision', 'Objective' => 'Objective', 'History' => 'History'], null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('category') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('title') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('content', 'Content') !!}
                {!! Form::textarea('content', null, ['class' => 'form-control mb-3 summernote', 'required']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>


@endsection
