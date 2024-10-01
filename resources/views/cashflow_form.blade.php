@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($cashflow, [
                'route' => isset($cashflow->id) ? ['cashflow.update', $cashflow->id] : 'cashflow.store',
                'method' => isset($cashflow->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data'
            ]) !!}

            <div class="form-group">
                {!! Form::label('date', 'Date') !!}
                {!! Form::date('date', $cashflow->date ?? now(), ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('date') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category', 'Category') !!}
                {!! Form::text('category', null, ['class' => 'form-control mb-3']) !!}
                <span class="text-danger">{!! $errors->first('category') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('description', 'Description') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('type', 'Type Cashflow') !!}
                <div class="form-check">
                    {!! Form::radio('type', 'income', $cashflow->type == 'income', ['class' => 'form-check-input', 'id' => 'income']) !!}
                    {!! Form::label('income', 'Income', ['class' => 'form-check-label']) !!}
                </div>

                <div class="form-check">
                    {!! Form::radio('type', 'expenses', $cashflow->type == 'expenses', ['class' => 'form-check-input', 'id' => 'expenses']) !!}
                    {!! Form::label('expenses', 'Expenses', ['class' => 'form-check-label']) !!}
                </div>
                <span class="text-danger">{!! $errors->first('type') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('amount', 'Amount') !!}
                {!! Form::number('amount', null, ['class' => 'form-control mb-3', 'required', 'min' => 0, 'step' => '0.01']) !!}
                <span class="text-danger">{!! $errors->first('amount') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">Upload Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>


            {!! Form::submit('Save', ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection
