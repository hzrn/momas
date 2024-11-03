@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($categoryinfo, [
                'route' => isset($categoryinfo->id) ? ['categoryinfo.update', $categoryinfo->id] : 'categoryinfo.store',
                'method' => isset($categoryinfo->id) ? 'PUT' : 'POST',
            ]) !!}

            <div class="form-group">
                {!! Form::label('name',  __('categoryinfo.category_name')) !!}
                {!! Form::text('name', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

            {!! Form::submit( __('categoryinfo.save'), ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
