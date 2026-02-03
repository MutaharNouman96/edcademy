<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Notification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .activity-card { background: linear-gradient(135deg, #f8f9ff 0%, #fff5f8 100%); border: 2px solid {{ $actionColor }}; border-radius: 12px; padding: 25px; margin: 20px 0; }
        .activity-header { display: flex; align-items: center; margin-bottom: 20px; }
        .activity-icon { width: 50px; height: 50px; background: {{ $actionColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; margin-right: 15px; flex-shrink: 0; }
        .activity-info h2 { margin: 0; color: #333; font-size: 20px; }
        .activity-meta { color: #666; font-size: 14px; margin-top: 5px; }
        .activity-details { background: white; padding: 20px; border-radius: 8px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; text-align: right; max-width: 60%; word-wrap: break-word; }
        .changes-section { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .changes-title { font-weight: 600; color: #333; margin-bottom: 15px; }
        .change-item { background: white; padding: 10px; border-radius: 4px; margin-bottom: 8px; border-left: 3px solid {{ $actionColor }}; }
        .change-field { font-weight: 600; color: #666; font-size: 12px; text-transform: uppercase; }
        .change-values { display: flex; justify-content: space-between; margin-top: 5px; }
        .change-old { color: #dc3545; text-decoration: line-through; flex: 1; }
        .change-arrow { margin: 0 10px; color: #666; }
        .change-new { color: #28a745; flex: 1; text-align: right; }
        .impact-section { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .impact-title { font-weight: 600; color: #856404; margin-bottom: 10px; }
        .actor-info { background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; display: flex; align-items: center; }
        .actor-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px; }
        .actor-details h3 { margin: 0; color: #333; font-size: 16px; }
        .actor-details p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        .action-buttons { text-align: center; margin: 30px 0; }
        .action-button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 0 10px 10px 0; }
        .role-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .role-admin { background: linear-gradient(135deg, #dc3545, #fd7e14); color: white; }
        .role-educator { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
        .role-student { background: linear-gradient(135deg, #6f42c1, #e83e8c); color: white; }
        .timestamp { color: #666; font-size: 12px; text-align: center; margin-top: 20px; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📢 Activity Notification</h1>
        </div>

        <div class="content">
            <p>Hello {{ $recipient->full_name }},</p>

            <p>You are receiving this notification because of recent activity on the Ed-Cademy platform.</p>

            <div class="activity-card">
                <div class="activity-header">
                    <div class="activity-icon">{{ $actionIcon }}</div>
                    <div class="activity-info">
                        <h2>{{ ucfirst($activity->action_type) }} {{ $activity->entity_type }}</h2>
                        <div class="activity-meta">
                            {{ $activity->created_at->format('M j, Y \a\t g:i A') }}
                            @if($activity->ip_address)
                                • IP: {{ $activity->ip_address }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="actor-info">
                    <div class="actor-avatar">{{ substr($actor->first_name, 0, 1) }}{{ substr($actor->last_name, 0, 1) }}</div>
                    <div class="actor-details">
                        <h3>{{ $actor->full_name }}</h3>
                        <p>
                            <span class="role-badge role-{{ $activity->user_role }}">
                                {{ ucfirst($activity->user_role) }}
                            </span>
                            • {{ $actor->email }}
                        </p>
                    </div>
                </div>

                <div class="activity-details">
                    <div class="detail-row">
                        <span class="detail-label">Action:</span>
                        <span class="detail-value">{{ ucfirst($activity->action_type) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Entity:</span>
                        <span class="detail-value">{{ $activity->entity_type }}</span>
                    </div>
                    @if($activity->entity_name)
                    <div class="detail-row">
                        <span class="detail-label">Entity Name:</span>
                        <span class="detail-value">{{ $activity->entity_name }}</span>
                    </div>
                    @endif
                    @if($activity->entity_id)
                    <div class="detail-row">
                        <span class="detail-label">Entity ID:</span>
                        <span class="detail-value">#{{ $activity->entity_id }}</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Description:</span>
                        <span class="detail-value">{{ $activity->description }}</span>
                    </div>
                </div>

                @if($activity->old_values || $activity->new_values)
                <div class="changes-section">
                    <div class="changes-title">🔄 Changes Made</div>
                    @if($activity->old_values && $activity->new_values)
                        @foreach($activity->new_values as $field => $newValue)
                            @php $oldValue = $activity->old_values[$field] ?? null; @endphp
                            @if($oldValue != $newValue)
                            <div class="change-item">
                                <div class="change-field">{{ ucwords(str_replace('_', ' ', $field)) }}</div>
                                <div class="change-values">
                                    <span class="change-old">{{ $oldValue }}</span>
                                    <span class="change-arrow">→</span>
                                    <span class="change-new">{{ $newValue }}</span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @elseif($activity->new_values)
                        @foreach($activity->new_values as $field => $value)
                        <div class="change-item">
                            <div class="change-field">{{ ucwords(str_replace('_', ' ', $field)) }}</div>
                            <div class="change-values">
                                <span class="change-new" style="text-align: left; flex: none;">{{ $value }}</span>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                @endif

                @if($activity->metadata)
                <div class="changes-section">
                    <div class="changes-title">📋 Additional Information</div>
                    @foreach($activity->metadata as $key => $value)
                    <div class="change-item">
                        <div class="change-field">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                        <div class="change-values">
                            <span class="change-new" style="text-align: left; flex: none;">
                                @if(is_array($value) || is_object($value))
                                    {{ json_encode($value) }}
                                @else
                                    {{ $value }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            @if($isAdminRecipient && in_array($activity->user_role, ['educator', 'student']))
            <div class="impact-section">
                <div class="impact-title">👀 Admin Action Required</div>
                <p>This activity may require your attention or follow-up. Please review the details above and take appropriate action if needed.</p>
            </div>
            @endif

            @if($isEducatorRecipient && $activity->user_role === 'student')
            <div class="impact-section">
                <div class="impact-title">🎓 Student Activity Alert</div>
                <p>A student has taken an action that may require your attention. This could be an enrollment, question, or feedback.</p>
            </div>
            @endif

            @if($isStudentRecipient && $activity->user_role === 'educator')
            <div class="impact-section">
                <div class="impact-title">👨‍🏫 Educator Update</div>
                <p>An educator has made changes that affect you. This could include course updates, new materials, or schedule changes.</p>
            </div>
            @endif

            <div class="action-buttons">
                @if($isAdminRecipient)
                    <a href="{{ route('admin.dashboard') }}" class="action-button">View Admin Dashboard</a>
                @elseif($isEducatorRecipient)
                    <a href="{{ route('educator.dashboard') }}" class="action-button">View Educator Dashboard</a>
                @else
                    <a href="{{ route('student.dashboard') }}" class="action-button">View Student Dashboard</a>
                @endif
                <a href="{{ route('web.contact.us') }}" class="action-button" style="background: linear-gradient(135deg, #6c757d, #495057);">Contact Support</a>
            </div>

            <div class="timestamp">
                Activity occurred at {{ $activity->created_at->format('l, F j, Y \a\t g:i:s A') }}
            </div>

            <p>This is an automated notification from the Ed-Cademy activity monitoring system.</p>
        </div>

        <div class="footer">
            <p>
                You received this notification because you are monitoring activity on Ed-Cademy.
            </p>
            <p>
                <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a> |
                <a href="{{ route('web.terms.and.conditions') }}">Terms of Service</a> |
                <a href="{{ route('web.contact.us') }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>