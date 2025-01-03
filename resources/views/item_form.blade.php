@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($item, [
                'route' => isset($item->id) ? ['item.update', $item->id] : 'item.store',
                'method' => isset($item->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data'
            ]) !!}

            <div class="form-group">
                {!! Form::label('name', __('item.name')) !!}
                {!! Form::text('name', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category_item_id', __('item.category')) !!}
                {!! Form::select('category_item_id', $categoryList, null, ['class' => 'form-control mb-3', 'required', 'placeholder' => 'Select a Category']) !!}
                <span class="text-danger">{!! $errors->first('category_item_id') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('description', __('item.description')) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('quantity', __('item.quantity')) !!}
                {!! Form::number('quantity', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('quantity') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('price', __('item.price')) !!}
                {!! Form::number('price', null, ['class' => 'form-control mb-3', 'step' => '0.01', 'required']) !!}
                <span class="text-danger">{!! $errors->first('price') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">{{ __('item.photo') }} (Format: JPEG, JPG, PNG)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('item.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('item.no_file') }}</span>
                    <span id="error-message" class="text-danger d-none"></span>
                </div>
            </div>

            {!! Form::submit(__('item.save'), ['class' => 'btn btn-success']) !!}

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
        const fileName = file ? file.name : '{{ __('item.no_file') }}';
        document.getElementById('file-name').textContent = fileName;

        const maxSize = 1.9 * 1024 * 1024; // 1.9MB in bytes
        const errorMessageElement = document.getElementById('error-message');

        if (file && file.size > maxSize) {
            errorMessageElement.textContent = '{{ __('committee.error_file_size') }}'; // Custom error message
            errorMessageElement.classList.remove('d-none');
        } else {
            errorMessageElement.classList.add('d-none');
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
        color: red;
    }
</style>

@endsection
