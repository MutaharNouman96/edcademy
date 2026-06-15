<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Payout Notification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { padding: 28px 30px; color: #fff; background: {{ $success ? '#198754' : '#dc3545' }}; }
        .content { padding: 30px; color: #333; line-height: 1.6; }
        .meta { background: #f8f9fa; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .meta dt { font-weight: 600; color: #666; }
        .meta dd { margin: 0 0 10px 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1 style="margin:0;font-size:22px;">Payout batch {{ $success ? 'completed' : 'failed' }}</h1>
    </div>
    <div class="content">
        <p>A payout batch has finished processing on the platform.</p>

        <dl class="meta">
            <dt>Batch ID</dt><dd>#{{ $batch->id }}</dd>
            <dt>Educator</dt><dd>{{ $educator->full_name }} ({{ $educator->email }})</dd>
            <dt>Amount</dt><dd>{{ $batch->currency }} {{ number_format($batch->total_net_amount, 2) }}</dd>
            <dt>Status</dt><dd>{{ ucfirst($batch->status) }}</dd>
            <dt>Processed by</dt><dd>{{ $batch->processed_by ?? 'system' }}</dd>
            @if($batch->processed_at)
                <dt>Processed at</dt><dd>{{ $batch->processed_at->format('M d, Y H:i') }}</dd>
            @endif
        </dl>

        @if(!$success && $errorMessage)
            <p style="color:#dc3545;"><strong>Error:</strong> {{ $errorMessage }}</p>
        @endif

        <p><a href="{{ route('admin.payout-batches.show', $batch) }}">View batch in admin panel</a></p>
    </div>
</div>
</body>
</html>
