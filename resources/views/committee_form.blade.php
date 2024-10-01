@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{ $title }}</h1>

            {!! Form::model($committee, [
                'route' => isset($committee->id) ? ['committee.update', $committee->id] : 'committee.store',
                'method' => isset($committee->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data'
            ]) !!}

            <!-- Name Field -->
            <div class="form-group mb-3">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

            <!-- Phone Number Field -->
            <div class="form-group mb-3">
                {!! Form::label('phone_num', 'Phone Number') !!}
                {!! Form::text('phone_num', null, ['class' => 'form-control', 'required', 'pattern' => '[0-9]*', 'inputmode' => 'numeric']) !!}
                <span class="text-danger">{!! $errors->first('phone_num') !!}</span>
            </div>

            <!-- Position Field -->
            <div class="form-group mb-3">
                {!! Form::label('position', 'Position') !!}
                {!! Form::text('position', null, ['class' => 'form-control', 'required']) !!}
                <span class="text-danger">{!! $errors->first('position') !!}</span>
            </div>

            <!-- Address Field -->
            <div class="form-group mb-3">
                {!! Form::label('address', 'Address') !!}
                {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                <span class="text-danger">{!! $errors->first('address') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">Upload Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>



            <!-- Save Button -->
            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection

