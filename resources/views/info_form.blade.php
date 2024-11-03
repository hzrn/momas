@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($info, [
                'route' => isset($info->id) ? ['info.update', $info->id] : 'info.store',
                'method' => isset($info->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data'
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
                {!! Form::label('content',  __('info.description') ) !!}
                {!! Form::textarea('content', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('content') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">{{ __('info.photo') }}</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('info.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('info.no_file') }}</span>
                </div>
            </div>

            {!! Form::submit(__('info.save') , ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    document.getElementById('choose-file-button').addEventListener('click', function() {
        document.getElementById('photo').click();
    });

    document.getElementById('photo').addEventListener('change', function(event) {
        const fileName = event.target.files.length ? event.target.files[0].name : '{{ __('info.no_file') }}';
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
