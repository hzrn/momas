@extends('layouts.app_adminkit')

@section('content')
<h1 class="h3 mb-3">{{$title}}</h1>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title">{{__('mosque.fill')}}</h5>
            </div>
            <div class="card-body pt-0">
                {!! Form::model($mosque, [
                    'method' => 'POST',
                    'route' => 'mosque.store'
                ]) !!}

                <!-- CSRF Token -->
                {!! csrf_field() !!}

                <div class="form-group mb-3">
                    <label for="name">{{__('mosque.name')}}</label>
                    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('name') !!}</span>
                </div>

                <div class="form-group mb-3">
                    <label for="address">{{__('mosque.address')}}</label>
                    {!! Form::text('address', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('address') !!}</span>
                </div>

                <div class="form-group">
                    {!! Form::label('phone_num', __('mosque.phone')) !!}
                    {!! Form::text('phone_num', null, [
                        'class' => 'form-control mb-3',
                        'required',
                        'pattern' => '[0-9]*',
                        'inputmode' => 'numeric',

                    ]) !!}
                    <span class="text-danger">{!! $errors->first('phone_num') !!}</span>
                </div>

                <div class="form-group mb-3">
                    {!! Form::label('email', __('mosque.email')) !!}
                    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
                    <span class="text-danger">{!! $errors->first('email') !!}</span>
                </div>

                {!! Form::submit(__('mosque.save'), ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
