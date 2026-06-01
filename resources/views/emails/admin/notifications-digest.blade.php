<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} Digest</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.5;">
    <h2 style="margin-bottom: 6px;">{{ $title }}</h2>
    <p style="margin-top: 0;">
        In the span of 12 hours ({{ $from->format('M j, Y g:i A') }} - {{ $to->format('M j, Y g:i A') }})
    </p>

    <p><strong>Total records:</strong> {{ $rows->count() }}</p>

    @if($rows->isEmpty())
        <p>No new records found in this time window.</p>
    @else
        <ul>
            @foreach($rows as $row)
                <li style="margin-bottom: 6px;">{{ $row }}</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
