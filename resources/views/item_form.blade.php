@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($item, [
                'route' => isset($item->id) ? ['item.update', $item->id] : 'item.store',
                'method' => isset($item->id) ? 'PUT' : 'POST',
            ]) !!}

            <div class="form-group">
                {!! Form::label('name', 'Item Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('name') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category_item_id', 'Category') !!}
                {!! Form::select('category_item_id', $categoryList, null, ['class' => 'form-control mb-3', 'required', 'placeholder' => 'Select a Category']) !!}
                <span class="text-danger">{!! $errors->first('category_item_id') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('quantity', 'Quantity') !!}
                {!! Form::number('quantity', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('quantity') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('price', 'Price') !!}
                {!! Form::number('price', null, ['class' => 'form-control mb-3', 'step' => '0.01', 'required']) !!}
                <span class="text-danger">{!! $errors->first('price') !!}</span>
            </div>

            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
