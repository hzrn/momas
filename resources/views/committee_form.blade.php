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
                {!! Form::label('name', __('committee.name')) !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

            <!-- Phone Number Field -->
            <div class="form-group mb-3">
                {!! Form::label('phone_num', __('committee.phone')) !!}
                {!! Form::text('phone_num', null, [
                    'class' => 'form-control',
                    'required',
                    'pattern' => '[0-9]*',
                    'inputmode' => 'numeric'
                ]) !!}
                <span class="text-danger">{!! $errors->first('phone_num') !!}</span>
            </div>

            <!-- Position Field -->
            <div class="form-group mb-3">
                {!! Form::label('position', __('committee.position')) !!}
                {!! Form::text('position', null, ['class' => 'form-control', 'required']) !!}
                <span class="text-danger">{!! $errors->first('position') !!}</span>
            </div>

            <!-- Address Field -->
            <div class="form-group mb-3">
                {!! Form::label('address', __('committee.address')) !!}
                {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
                <span class="text-danger">{!! $errors->first('address') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">{{ __('committee.photo') }} (Format : JPEG, JPG, PNG / Max : 2MB)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('committee.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('committee.no_file') }}</span>
                </div>
            </div>


            <!-- Save Button -->
            {!! Form::submit(__('committee.save'), ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script src="https://widget.cloudinary.com/v2.0/global/all.js"></script>


<script>
    document.getElementById('choose-file-button').addEventListener('click', function() {
        document.getElementById('photo').click();
    });

    document.getElementById('photo').addEventListener('change', function(event) {
        const fileName = event.target.files.length ? event.target.files[0].name : '{{ __('committee.no_file') }}';
        document.getElementById('file-name').textContent = fileName;
    });
</script>

<style>
    .custom-file-input-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
}

#file-name {
    font-style: italic;
    color: #6c757d;
}

</style>
@endsection
