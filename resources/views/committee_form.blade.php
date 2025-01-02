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

            <div class="form-group mb-3">
                <label for="photo">{{ __('committee.photo') }} (Format: JPEG, JPG, PNG / Max: 2MB)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('committee.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('committee.no_file') }}</span>
                </div>
                <span id="file-error" class="text-danger d-none"></span>
            </div>



            <!-- Save Button -->
            {!! Form::submit(__('committee.save'), ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('photo');
    const chooseFileButton = document.getElementById('choose-file-button');
    const fileNameDisplay = document.getElementById('file-name');
    const fileErrorDisplay = document.getElementById('file-error');

    const maxFileSize = 2 * 1024 * 1024; // 2MB

    chooseFileButton.addEventListener('click', () => {
        fileErrorDisplay.classList.add('d-none'); // Hide any previous error
        fileInput.click(); // Trigger the hidden file input
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];

        if (file) {
            // Validate file size
            if (file.size > maxFileSize) {
                fileErrorDisplay.textContent = 'The file size exceeds the 2MB limit. Please choose a smaller file.';
                fileErrorDisplay.classList.remove('d-none');
                fileInput.value = ''; // Clear the input
                fileNameDisplay.textContent = '{{ __('committee.no_file') }}'; // Reset file name display
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                fileErrorDisplay.textContent = 'Invalid file format. Please upload a JPEG, JPG, or PNG file.';
                fileErrorDisplay.classList.remove('d-none');
                fileInput.value = ''; // Clear the input
                fileNameDisplay.textContent = '{{ __('committee.no_file') }}'; // Reset file name display
                return;
            }

            // If valid, display the file name
            fileNameDisplay.textContent = file.name;
            fileErrorDisplay.classList.add('d-none'); // Hide any error
        } else {
            fileNameDisplay.textContent = '{{ __('committee.no_file') }}'; // No file selected
        }
    });
});

</script>

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
