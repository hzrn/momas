<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('cashflow.list_title') }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .date {
            text-align: right;
            font-size: 12px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $mosqueName }}</h2>
        <h3>{{ __('cashflow.list_title') }}</h3>
    </div>

    <div class="date">
        {{ __('cashflow.print_date') }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('cashflow.no') }}</th>
                <th>{{ __('cashflow.date') }}</th>
                <th>{{ __('cashflow.category') }}</th>
                <th>{{ __('cashflow.description') }}</th>
                <th>{{ __('cashflow.income') }}</th>
                <th>{{ __('cashflow.expenses') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cashflow as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->date->format('d-m-Y') }}</td>
                    <td>{{ $item->category ?? 'Public' }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->type === 'income' ? formatRM($item->amount) : '-' }}</td>
                    <td>{{ $item->type === 'expenses' ? formatRM($item->amount) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
