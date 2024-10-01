@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($categoryitem, [
                'route' => isset($categoryitem->id) ? ['categoryitem.update', $categoryitem->id] : 'categoryitem.store',
                'method' => isset($categoryitem->id) ? 'PUT' : 'POST',
            ]) !!}

            <div class="form-group">
                {!! Form::label('name', 'Category Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

           
            
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
