@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Fill the mosque form as required</h5>
            </div>
            <div class="card-body">
                {!! Form::model($mosque, [
                    'method' => 'POST',
                    'route' => 'mosque.store'
                ]) !!}

                <div class="form-group mb-3">
                    <label for="name">Mosque Name</label>
                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                </div>
                <div class="form-group mb-3">
                    <label for="name">Mosque Address</label>
                    {!! Form::text('address', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('address') !!}</span>
                </div>
                <div class="form-group">
                    {!! Form::label('phone_num', 'Phone Number') !!}
                    {!! Form::text('phone_num', null, [
                        'class' => 'form-control mb-3',
                        'required',
                        'pattern' => '[0-9]*',
                        'inputmode' => 'numeric'
                    ]) !!}
                    <span class="text-danger">{!! $errors->first('phone_num') !!}</span>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                </div>
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
