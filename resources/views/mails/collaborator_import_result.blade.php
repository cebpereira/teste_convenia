<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Collaborator Import Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        .summary {
            margin-bottom: 20px;
        }

        ul {
            padding-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        .error-record {
            background-color: #f8d7da;
            padding: 10px;
            border-left: 4px solid #f5c2c7;
        }

        .error-record em {
            color: #721c24;
        }
    </style>
</head>

<body>
    <h2>Collaborator Import Summary</h2>

    <div class="summary">
        <p><strong>Successfully imported:</strong> {{ $imported ?? '-' }}</p>
        <p><strong>Skipped (errors or duplicates):</strong> {{ count($skipped) ?? '-' }}</p>
    </div>

    @if (!empty($skipped))
        <h4>Details of Ignored Records:</h4>
        <ul>
            @foreach ($skipped as $error)
                <li class="error-record">
                    <strong>{{ $error['name'] ?? 'N/A' }}</strong> – {{ $error['email'] ?? 'N/A' }} /
                    {{ $error['cpf'] ?? 'N/A' }}<br>
                    <em>Reason:</em> {{ $error['reason'] ?? 'Unknown' }}
                </li>
            @endforeach
        </ul>
    @endif
</body>

</html>
