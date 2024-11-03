<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile List</title>
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
        <h3>Profile List</h3>
    </div>

    <div class="date">
        Print Date: {{ \Carbon\Carbon::now()->format('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Category</th>
                <th>Content</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($profile as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{!! nl2br(e($item->content)) !!}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
