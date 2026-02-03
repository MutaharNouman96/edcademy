<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Weekly Learning Report</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: "📊"; font-size: 60px; position: absolute; top: 10px; right: 20px; opacity: 0.3; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .header .subtitle { color: #e8eaf6; margin: 10px 0 0 0; font-size: 16px; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .welcome-message { font-size: 18px; margin-bottom: 20px; color: #667eea; font-weight: 600; }
        .stats-overview { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 30px 0; }
        .stat-card { background: linear-gradient(135deg, #f8f9ff 0%, #fff5f8 100%); padding: 20px; border-radius: 12px; text-align: center; border: 1px solid #e9ecef; }
        .stat-number { font-size: 28px; font-weight: bold; color: #667eea; display: block; margin-bottom: 5px; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; }
        .progress-section { background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 30px 0; }
        .progress-title { font-weight: 600; color: #333; margin-bottom: 20px; font-size: 18px; }
        .course-progress { margin-bottom: 20px; padding: 20px; background: white; border-radius: 8px; border: 1px solid #e9ecef; }
        .course-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .course-name { font-weight: 600; color: #333; font-size: 16px; }
        .progress-percentage { font-weight: bold; color: #28a745; }
        .progress-bar { width: 100%; height: 8px; background: #e9ecef; border-radius: 4px; margin: 10px 0; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 4px; }
        .activity-section { background-color: #f0f8ff; padding: 25px; border-radius: 8px; margin: 30px 0; }
        .activity-title { font-weight: 600; color: #0056b3; margin-bottom: 20px; font-size: 18px; }
        .activity-item { display: flex; align-items: center; margin-bottom: 15px; padding: 15px; background: white; border-radius: 6px; }
        .activity-icon { width: 40px; height: 40px; background: #667eea; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 18px; }
        .activity-content { flex: 1; }
        .activity-content h4 { margin: 0 0 5px 0; color: #333; font-size: 14px; }
        .activity-content p { margin: 0; color: #666; font-size: 12px; }
        .upcoming-section { background-color: #fff5f5; padding: 25px; border-radius: 8px; margin: 30px 0; }
        .upcoming-title { font-weight: 600; color: #e83e8c; margin-bottom: 20px; font-size: 18px; }
        .session-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; background: white; border-radius: 6px; margin-bottom: 10px; border-left: 4px solid #e83e8c; }
        .session-details h4 { margin: 0 0 5px 0; color: #333; font-size: 14px; }
        .session-details p { margin: 0; color: #666; font-size: 12px; }
        .session-time { text-align: right; }
        .session-time .date { font-weight: 600; color: #333; }
        .session-time .time { color: #666; font-size: 12px; }
        .achievements-section { background: linear-gradient(135deg, #fff8e1 0%, #fff3c4 100%); padding: 25px; border-radius: 8px; margin: 30px 0; }
        .achievements-title { font-weight: 600; color: #f57c00; margin-bottom: 20px; font-size: 18px; }
        .achievement-item { display: flex; align-items: center; margin-bottom: 15px; padding: 15px; background: white; border-radius: 6px; }
        .achievement-badge { width: 40px; height: 40px; background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 18px; }
        .achievement-content h4 { margin: 0 0 5px 0; color: #333; font-size: 14px; }
        .achievement-content p { margin: 0; color: #666; font-size: 12px; }
        .recommendations-section { background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 30px 0; }
        .recommendations-title { font-weight: 600; color: #333; margin-bottom: 20px; font-size: 18px; }
        .recommendation-item { display: flex; align-items: flex-start; margin-bottom: 15px; padding: 15px; background: white; border-radius: 6px; }
        .recommendation-icon { width: 40px; height: 40px; background: #28a745; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 18px; flex-shrink: 0; }
        .recommendation-content { flex: 1; }
        .recommendation-content h4 { margin: 0 0 8px 0; color: #333; font-size: 14px; }
        .recommendation-content p { margin: 0 0 10px 0; color: #666; font-size: 12px; line-height: 1.4; }
        .recommendation-link { display: inline-block; background: #28a745; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Weekly Learning Report</h1>
            <div class="subtitle">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}</div>
        </div>

        <div class="content">
            <div class="welcome-message">
                Hi {{ $student->full_name }}!
            </div>

            <p>Here's your personalized learning report for this week. Keep up the great work on your educational journey!</p>

            <div class="stats-overview">
                <div class="stat-card">
                    <span class="stat-number">{{ $reportData['courses_enrolled'] ?? 0 }}</span>
                    <span class="stat-label">Courses Enrolled</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $reportData['lessons_completed'] ?? 0 }}</span>
                    <span class="stat-label">Lessons Completed</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $reportData['hours_learned'] ?? 0 }}</span>
                    <span class="stat-label">Hours Learned</span>
                </div>
                <div class="stat-card">
                    <span class="stat-number">{{ $reportData['current_streak'] ?? 0 }}</span>
                    <span class="stat-label">Day Streak</span>
                </div>
            </div>

            @if(isset($reportData['course_progress']) && count($reportData['course_progress']) > 0)
            <div class="progress-section">
                <div class="progress-title">📚 Your Course Progress</div>
                @foreach($reportData['course_progress'] as $course)
                <div class="course-progress">
                    <div class="course-header">
                        <span class="course-name">{{ $course['title'] ?? 'Course Title' }}</span>
                        <span class="progress-percentage">{{ $course['progress_percentage'] ?? 0 }}% Complete</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $course['progress_percentage'] ?? 0 }}%;"></div>
                    </div>
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                        {{ $course['lessons_completed'] ?? 0 }} of {{ $course['total_lessons'] ?? 0 }} lessons completed
                        @if(isset($course['last_accessed']))
                            • Last accessed {{ $course['last_accessed']->diffForHumans() }}
                        @endif
                    </p>
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($reportData['weekly_activity']) && count($reportData['weekly_activity']) > 0)
            <div class="activity-section">
                <div class="activity-title">🔥 Your Learning Activity This Week</div>
                @foreach($reportData['weekly_activity'] as $activity)
                <div class="activity-item">
                    <div class="activity-icon">{{ $activity['icon'] ?? '📖' }}</div>
                    <div class="activity-content">
                        <h4>{{ $activity['title'] ?? 'Activity' }}</h4>
                        <p>{{ $activity['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($reportData['upcoming_sessions']) && count($reportData['upcoming_sessions']) > 0)
            <div class="upcoming-section">
                <div class="upcoming-title">📅 Upcoming Sessions</div>
                @foreach($reportData['upcoming_sessions'] as $session)
                <div class="session-item">
                    <div class="session-details">
                        <h4>{{ $session['title'] ?? 'Session with Educator' }}</h4>
                        <p>{{ $session['description'] ?? '' }}</p>
                    </div>
                    <div class="session-time">
                        <div class="date">{{ $session['date'] ?? '' }}</div>
                        <div class="time">{{ $session['time'] ?? '' }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($reportData['achievements']) && count($reportData['achievements']) > 0)
            <div class="achievements-section">
                <div class="achievements-title">🏆 This Week's Achievements</div>
                @foreach($reportData['achievements'] as $achievement)
                <div class="achievement-item">
                    <div class="achievement-badge">{{ $achievement['badge'] ?? '🎯' }}</div>
                    <div class="achievement-content">
                        <h4>{{ $achievement['title'] ?? 'Achievement Unlocked' }}</h4>
                        <p>{{ $achievement['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($reportData['recommendations']) && count($reportData['recommendations']) > 0)
            <div class="recommendations-section">
                <div class="recommendations-title">💡 Personalized Recommendations</div>
                @foreach($reportData['recommendations'] as $recommendation)
                <div class="recommendation-item">
                    <div class="recommendation-icon">{{ $recommendation['icon'] ?? '📚' }}</div>
                    <div class="recommendation-content">
                        <h4>{{ $recommendation['title'] ?? 'Recommendation' }}</h4>
                        <p>{{ $recommendation['description'] ?? '' }}</p>
                        @if(isset($recommendation['link']))
                            <a href="{{ $recommendation['link'] }}" class="recommendation-link">Learn More</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('student.dashboard') }}" class="cta-button">Continue Learning</a>
            </div>

            <p><strong>💪 Keep up the momentum!</strong> Consistent learning leads to lasting success. Set small, achievable goals and celebrate your progress along the way.</p>

            <p><strong>Questions or need help?</strong> Our support team is always here to assist you with your learning journey.</p>

            <p><strong>The Ed-Cademy Learning Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This weekly report was generated for {{ $student->email }}.
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