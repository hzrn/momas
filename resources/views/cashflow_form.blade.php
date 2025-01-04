@extends('layouts.app_adminkit')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="container mt-3">
            <h1 class="h3 mb-3">{{$title}}</h1>

            {!! Form::model($cashflow, [
                'route' => isset($cashflow->id) ? ['cashflow.update', $cashflow->id] : 'cashflow.store',
                'method' => isset($cashflow->id) ? 'PUT' : 'POST',
                'enctype' => 'multipart/form-data',
                'id' => 'cashflow-form'
            ]) !!}

            <div class="form-group">
                {!! Form::label('date', __('cashflow.date')) !!}
                {!! Form::date('date', $cashflow->date ?? now(), ['class' => 'form-control mb-3', 'required']) !!}
                <span class="text-danger">{!! $errors->first('date') !!}</span>
            </div>

            <div class="form-group mb-3">
                {!! Form::label('type', __('cashflow.type')) !!}
                <div class="form-check">
                    {!! Form::radio('type', 'income', $cashflow->type == 'income', ['class' => 'form-check-input', 'id' => 'income']) !!}
                    {!! Form::label('income', __('cashflow.income'), ['class' => 'form-check-label']) !!}
                </div>

                <div class="form-check">
                    {!! Form::radio('type', 'expenses', $cashflow->type == 'expenses', ['class' => 'form-check-input', 'id' => 'expenses']) !!}
                    {!! Form::label('expenses', __('cashflow.expenses'), ['class' => 'form-check-label']) !!}
                </div>
                <span class="text-danger">{!! $errors->first('type') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('category', __('cashflow.category')) !!}
                {!! Form::select(
                    'category',
                    [],
                    $cashflow->category ?? null,
                    [
                        'class' => 'form-control mb-3',
                        'placeholder' => __('cashflow.select'),
                        'id' => 'category',
                        'data-selected-value' => $cashflow->category ?? ''
                    ]
                ) !!}
                <span class="text-danger">{!! $errors->first('category') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('description', __('cashflow.description')) !!}
                {!! Form::textarea('description', null, ['class' => 'form-control mb-3',]) !!}
                <span class="text-danger">{!! $errors->first('description') !!}</span>
            </div>

            <div class="form-group">
                {!! Form::label('amount', __('cashflow.amount')) !!}
                {!! Form::number('amount', null, ['class' => 'form-control mb-3', 'required', 'min' => 0, 'step' => '0.01']) !!}
                <span class="text-danger">{!! $errors->first('amount') !!}</span>
            </div>

            <!-- Image Upload Field -->
            <div class="form-group mb-3">
                <label for="photo">{{ __('cashflow.photo') }} (Format : JPEG, JPG, PNG / Max : 2MB)</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('cashflow.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('cashflow.no_file') }}</span>
                    <span id="error-message" class="text-danger d-none"></span>
                </div>
            </div>

            {!! Form::submit(__('cashflow.save') , ['class' => 'btn btn-success', 'id' => 'submit-button']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    const donationOptions = {
        'Sadaqah': '{{ __('cashflow.sadaqah') }}',
        'Zakat': '{{ __('cashflow.zakat') }}',
        'Waqf': '{{ __('cashflow.waqf') }}',
        'Donation': '{{ __('cashflow.donation') }}',
        'Fitrah': '{{ __('cashflow.fitrah') }}',
        'General': '{{ __('cashflow.general') }}'
    };

    const expenseOptions = {
        'Utilities': '{{ __('cashflow.utilities') }}',
        'Maintenance': '{{ __('cashflow.maintenance') }}',
        'Salaries': '{{ __('cashflow.salaries') }}',
        'Supplies': '{{ __('cashflow.supplies') }}',
        'Events': '{{ __('cashflow.events') }}',
        'Insurance': '{{ __('cashflow.insurance') }}',
        'Miscellaneous': '{{ __('cashflow.miscellaneous') }}'
    };

    function updateCategoryDropdown() {
        const selectedType = document.querySelector('input[name="type"]:checked').value;
        const categoryDropdown = document.getElementById('category');
        const currentValue = categoryDropdown.dataset.selectedValue || categoryDropdown.value;

        categoryDropdown.innerHTML = '';

        const options = selectedType === 'expenses' ? expenseOptions : donationOptions;

        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = '{{ __('cashflow.select') }}';
        categoryDropdown.appendChild(placeholderOption);

        for (const [key, value] of Object.entries(options)) {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = value;

            if (key === currentValue) {
                option.selected = true;
            }

            categoryDropdown.appendChild(option);
        }
    }

    document.querySelectorAll('input[name="type"]').forEach((radio) => {
        radio.addEventListener('change', updateCategoryDropdown);
    });

    document.addEventListener('DOMContentLoaded', updateCategoryDropdown);
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
