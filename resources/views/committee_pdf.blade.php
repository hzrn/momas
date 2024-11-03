<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"> <!-- Dynamically set the language -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('committee.list_title') }}</title>
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
        <h3>{{ __('committee.list_title') }}</h3>
    </div>

    <div class="date">
        {{ __('committee.print_date') }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('committee.no') }}</th>
                <th>{{ __('committee.name') }}</th>
                <th>{{ __('committee.position') }}</th>
                <th>{{ __('committee.phone') }}</th>
                <th>{{ __('committee.address') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($committee as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->position }}</td>
                    <td>{{ $item->phone_num }}</td>
                    <td>{{ $item->address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
