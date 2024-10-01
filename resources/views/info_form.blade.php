@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($info, [
                'route' => isset($info->id) ? ['info.update', $info->id] : 'info.store',
                'method' => isset($info->id) ? 'PUT' : 'POST',
            ]) !!}

            <div class="form-group">
                {!! Form::label('title', 'Title') !!}
                {!! Form::text('title', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('title') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category_info_id', 'Category') !!}
                {!! Form::select('category_info_id', $categoryList, null, ['class' => 'form-control mb-3', 'required', 'placeholder' => 'Select a Category']) !!}
                <span class="text-danger">{!! $errors->first('category_info_id') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('date', 'Date and Time') !!}
                {!! Form::input('datetime-local', 'date', $info->date ? \Carbon\Carbon::parse($info->date)->format('Y-m-d\TH:i') : null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('date') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('content', 'Content') !!}
                {!! Form::textarea('content', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('content') !!}</span>
            </div>
            
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
