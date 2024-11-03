<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
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
        <h3>{{__('item.list_title')}}</h3>
    </div>

    <div class="date">
        {{ __('item.print_date') }}: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('item.no') }}</th>
                <th>{{ __('item.name') }}</th>
                <th>{{ __('item.category') }}</th>
                <th>{{ __('item.description') }}</th>
                <th>{{ __('item.quantity') }}</th>
                <th>{{ __('item.price') }}</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? 'N/A' }}</td>
                    <td>{{ strip_tags($item->description ?? '-') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ formatRM($item->price) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
