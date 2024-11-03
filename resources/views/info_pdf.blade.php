<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info List</title>
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
        <h3>{{ __('info.list_title') }}</h3>
    </div>

    <div class="date">
        {{ __('info.print_date') }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('info.no') }}</th>
                <th>{{ __('info.title') }}</th>
                <th>{{ __('info.category') }}</th>
                <th>{{ __('info.date') }}</th>
                <th>{{ __('info.description') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($info as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y H:i') }}</td>
                    <td>{{ $item->content ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
