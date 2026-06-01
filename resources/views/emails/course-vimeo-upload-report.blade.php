<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vimeo Upload Report — Ed-Cademy</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; color: #333; }
        .container { max-width: 680px; margin: 0 auto; background-color: #ffffff; }
        .header { padding: 36px 30px; text-align: center; color: #ffffff; background: linear-gradient(135deg, {{ $hasError ? '#dc3545 0%, #6c757d 100%' : '#00838f 0%, #28a745 100%' }}); }
        .header h1 { margin: 0; font-size: 22px; font-weight: 600; }
        .header p { margin: 6px 0 0; font-size: 13px; opacity: .9; }
        .content { padding: 32px 30px; line-height: 1.55; }
        .summary-grid { display: table; width: 100%; border-collapse: separate; border-spacing: 8px; margin: 18px 0 26px; }
        .summary-cell { display: table-cell; width: 33%; padding: 14px; border-radius: 8px; text-align: center; border: 1px solid #e9ecef; background: #f8f9fa; }
        .summary-cell .label { font-size: 11px; text-transform: uppercase; color: #6c757d; letter-spacing: .5px; }
        .summary-cell .value { font-size: 22px; font-weight: 700; margin-top: 4px; }
        .summary-cell.ok .value { color: #28a745; }
        .summary-cell.fail .value { color: #dc3545; }
        .summary-cell.total .value { color: #00838f; }
        table.results { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        table.results th, table.results td { padding: 10px 12px; border: 1px solid #e9ecef; text-align: left; vertical-align: top; }
        table.results th { background: #f1f5f7; font-weight: 600; color: #555; }
        .status-pill { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-pill.ok { background: #e7f7ed; color: #1e7e34; }
        .status-pill.fail { background: #fdecec; color: #c82333; }
        .note-box { border-left: 4px solid #ffc107; background: #fff8e1; padding: 14px 18px; margin: 16px 0; border-radius: 6px; font-size: 13px; color: #6b5200; }
        .course-meta { background: #f8f9fa; border-radius: 8px; padding: 16px 20px; margin: 12px 0 24px; font-size: 14px; }
        .course-meta div { margin: 4px 0; }
        .course-meta .key { color: #6c757d; display: inline-block; min-width: 110px; }
        .footer { background: #f8f9fa; padding: 22px 30px; text-align: center; color: #777; font-size: 12px; }
        a { color: #00838f; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $hasError ? 'Vimeo Upload Completed with Errors' : 'Vimeo Upload Completed Successfully' }}</h1>
            <p>Scheduled 5 minutes after course approval</p>
        </div>

        <div class="content">
            <p>Hi Admin,</p>
            <p>
                The automated Vimeo upload job has finished for the recently approved course below.
                See the per-lesson breakdown for details.
            </p>

            <div class="course-meta">
                <div><span class="key">Course:</span> <strong>#{{ $course->id }} — {{ $course->title }}</strong></div>
                @if($educator)
                    <div><span class="key">Educator:</span> {{ $educator->full_name ?? $educator->name ?? $educator->email }}</div>
                @endif
                <div><span class="key">Approved at:</span> {{ now()->format('M j, Y \a\t g:i A') }}</div>
                <div><span class="key">Status:</span>
                    <span class="status-pill {{ $hasError ? 'fail' : 'ok' }}">
                        {{ $hasError ? 'Needs Attention' : 'All Good' }}
                    </span>
                </div>
            </div>

            <div class="summary-grid">
                <div class="summary-cell total">
                    <div class="label">Total Lessons</div>
                    <div class="value">{{ $totalCount }}</div>
                </div>
                <div class="summary-cell ok">
                    <div class="label">Uploaded</div>
                    <div class="value">{{ $successCount }}</div>
                </div>
                <div class="summary-cell fail">
                    <div class="label">Failed</div>
                    <div class="value">{{ $failureCount }}</div>
                </div>
            </div>

            @if($note)
                <div class="note-box"><strong>Note:</strong> {{ $note }}</div>
            @endif

            @if(count($results) > 0)
                <table class="results">
                    <thead>
                        <tr>
                            <th style="width:60px;">Lesson</th>
                            <th>Title</th>
                            <th style="width:100px;">Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $row)
                            <tr>
                                <td>#{{ $row['lesson_id'] ?? '—' }}</td>
                                <td>{{ $row['title'] ?? 'Untitled' }}</td>
                                <td>
                                    <span class="status-pill {{ !empty($row['success']) ? 'ok' : 'fail' }}">
                                        {{ !empty($row['success']) ? 'Success' : 'Failed' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $row['message'] ?? '' }}
                                    @if(!empty($row['link']))
                                        <div style="margin-top:4px;">
                                            <a href="{{ $row['link'] }}" target="_blank">{{ $row['link'] }}</a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <p style="margin-top: 26px; font-size: 13px; color: #666;">
                Full response payloads and stack traces are written to
                <code>storage/logs/laravel.log</code> under the
                <code>UploadCourseVideosToVimeoJob</code> context.
            </p>
        </div>

        <div class="footer">
            This is an automated notification from the Ed-Cademy Vimeo upload pipeline.<br>
            © {{ date('Y') }} Ed-Cademy. All rights reserved.
        </div>
    </div>
</body>
</html>
