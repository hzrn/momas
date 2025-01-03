@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($info, [
                'route' => isset($info->id) ? ['info.update', $info->id] : 'info.store',
                'method' => isset($info->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data',
                'id' => 'info-form'
            ]) !!}

            <div class="form-group">
                {!! Form::label('title',  __('info.title') ) !!}
                {!! Form::text('title', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('title') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category_info_id',  __('info.category') ) !!}
                {!! Form::select('category_info_id', $categoryList, null, ['class' => 'form-control mb-3', 'required', 'placeholder' => 'Select a Category']) !!}
                <span class="text-danger">{!! $errors->first('category_info_id') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('date', __('info.date') ) !!}
                {!! Form::input('datetime-local', 'date', $info->date ? \Carbon\Carbon::parse($info->date)->format('Y-m-d\TH:i') : null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('date') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('description',  __('info.description') ) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('reminder_date', __('info.reminder_date')) !!}
                {!! Form::input('date', 'reminder_date', $info->reminder_date ? \Carbon\Carbon::parse($info->reminder_date)->format('Y-m-d') : null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('reminder_date') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">{{ __('info.photo') }} (Format : JPEG, JPG, PNG / Max : 2MB)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('info.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('info.no_file') }}</span>
                    <span id="error-message" class="text-danger d-none"></span>
                </div>
            </div>

            {!! Form::submit(__('info.save') , ['class' => 'btn btn-success', 'id' => 'submit-button']]) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    document.getElementById('choose-file-button').addEventListener('click', function() {
        document.getElementById('photo').click();
    });

    document.getElementById('photo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const fileName = file ? file.name : '{{ __('info.no_file') }}';
        document.getElementById('file-name').textContent = fileName;

        const maxSize = 1.9 * 1024 * 1024; // 1.9MB in bytes


        if (file && file.size > maxSize) {
            document.getElementById('error-message').textContent = '{{ __('info.error_file_size') }}'; // Add custom error message
            document.getElementById('error-message').classList.remove('d-none');
            document.getElementById('submit-button').disabled = true; // Disable the submit button
        } else {
            document.getElementById('error-message').classList.add('d-none');
            document.getElementById('submit-button').disabled = false; // Enable the submit button
        }
    });

        // Handle form submission
        document.getElementById('info-form').addEventListener('submit', function(event) {
        const fileInput = document.getElementById('photo');
        const file = fileInput.files[0];
        const maxSize = 1.9 * 1024 * 1024; // 2MB in bytes

        if (file && file.size > maxSize) {
            event.preventDefault();
            alert('{{ __('info.error_file_size') }}');
        }
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

    #error-message {
        font-size: 0.9rem;
    }
</style>

@endsection
