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
                {!! Form::select('category', [], null, ['class' => 'form-control mb-3', 'placeholder' => __('cashflow.select'), 'id' => 'category']) !!}
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
                <label for="photo">{{ __('cashflow.photo') }}</label>
                <div class="custom-file-input-wrapper">
                    <input type="file" name="photo" id="photo" accept="image/*" class="d-none">
                    <button type="button" class="btn btn-secondary" id="choose-file-button">
                        {{ __('cashflow.choose_file') }}
                    </button>
                    <span id="file-name">{{ __('cashflow.no_file') }}</span>
                </div>
            </div>

            <!-- Save Button -->
            {!! Form::submit(__('cashflow.save'), ['class' => 'btn btn-success']) !!}

            {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    // Initial category options for donations and expenses
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

    // Function to update category dropdown based on selected type
    function updateCategoryDropdown() {
        const selectedType = document.querySelector('input[name="type"]:checked').value;
        const categoryDropdown = document.getElementById('category');

        // Clear existing options
        categoryDropdown.innerHTML = '';

        // Set options based on type
        let options = selectedType === 'expenses' ? expenseOptions : donationOptions;

        // Add a placeholder option
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = '{{ __('cashflow.select') }}';
        categoryDropdown.appendChild(placeholderOption);

        // Populate dropdown with relevant options
        for (const [key, value] of Object.entries(options)) {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = value;
            categoryDropdown.appendChild(option);
        }
    }

    // Add event listeners to update the dropdown on radio button change
    document.querySelectorAll('input[name="type"]').forEach((radio) => {
        radio.addEventListener('change', updateCategoryDropdown);
    });

    // Initial call to set correct options if the form is being edited
    document.addEventListener('DOMContentLoaded', updateCategoryDropdown);

    document.getElementById('choose-file-button').addEventListener('click', function() {
        document.getElementById('photo').click();
    });

    document.getElementById('photo').addEventListener('change', function(event) {
        const fileName = event.target.files.length ? event.target.files[0].name : '{{ __('cashflow.no_file') }}';
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
