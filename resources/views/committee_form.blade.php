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
                <label for="photo">{{ __('committee.photo') }} (Format: JPEG, JPG, PNG / Max: 2MB)</label>
                <div class="custom-file-input-wrapper">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('committee.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('committee.no_file') }}</span>
                </div>
            </div>

            <!-- Hidden input to store Cloudinary URL -->
            <input type="hidden" name="photo" id="photo">

            <!-- Save Button -->
            {!! Form::submit(__('committee.save'), ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}
        </div>
    </div>
</div>

<script src="https://widget.cloudinary.com/v2.0/global/all.js"></script>

<script>
document.getElementById('choose-file-button').addEventListener('click', function() {
    cloudinary.openUploadWidget({
        cloud_name: 'dlbbfwofl', // Replace with your Cloudinary cloud name
        upload_preset: 'Momas-fyp', // Use the upload preset you created in Cloudinary
        cropping: true,
        max_file_size: 2 * 1024 * 1024, // 2MB limit
        sources: ['local', 'url', 'camera', 'dropbox', 'facebook']
    }, function(error, result) {
        if (error) {
            console.log(error);
            alert("Error uploading image.");
        } else if (result && result[0]) {
            console.log(result);
            document.getElementById('file-name').textContent = result[0].original_filename;
            document.getElementById('photo').value = result[0].secure_url; // Store the Cloudinary URL in a hidden field
        } else {
            console.error("No result returned from Cloudinary");
        }
    });
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
