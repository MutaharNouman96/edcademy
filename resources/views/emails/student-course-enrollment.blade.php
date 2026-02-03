<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment Confirmation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .welcome-message { font-size: 18px; margin-bottom: 20px; color: #667eea; font-weight: 600; }
        .order-summary { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .order-summary h3 { margin-top: 0; color: #333; }
        .courses-grid { display: grid; gap: 20px; margin: 30px 0; }
        .course-card { background: linear-gradient(135deg, #f8f9ff 0%, #fff5f8 100%); border: 1px solid #e9ecef; border-radius: 12px; padding: 20px; transition: transform 0.2s; }
        .course-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .course-header { display: flex; align-items: center; margin-bottom: 15px; }
        .course-icon { width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; margin-right: 15px; }
        .course-title { font-size: 18px; font-weight: 600; color: #333; margin: 0; }
        .course-meta { color: #666; font-size: 14px; margin: 5px 0; }
        .course-description { color: #555; margin-bottom: 15px; line-height: 1.5; }
        .access-info { background-color: #e8f5e8; border: 1px solid #c8e6c9; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .access-title { font-weight: 600; color: #2e7d32; margin-bottom: 10px; }
        .quick-start { background-color: #f8f9fa; padding: 25px; margin: 30px 0; border-radius: 8px; border-left: 4px solid #667eea; }
        .quick-start h4 { margin-top: 0; color: #667eea; }
        .step { margin-bottom: 12px; display: flex; align-items: flex-start; }
        .step-number { background: #667eea; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .resources { background-color: #f0f4ff; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .resource-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px; }
        .resource-item { background: white; padding: 15px; border-radius: 6px; text-align: center; border: 1px solid #e9ecef; }
        .resource-item h5 { margin: 0 0 8px 0; color: #333; font-size: 14px; }
        .resource-item a { color: #667eea; text-decoration: none; font-weight: 500; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .support-section { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Course Enrollment Confirmed!</h1>
        </div>

        <div class="content">
            <div class="welcome-message">
                Congratulations, {{ $student->full_name }}!
            </div>

            <p>You've successfully enrolled in your course(s)! We're excited to be part of your learning journey. Your courses are now available in your student dashboard.</p>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Purchase Date:</strong> {{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
                <p><strong>Items Purchased:</strong> {{ $orderItems->count() }}</p>
            </div>

            <h3 style="color: #333; margin-top: 30px;">Your Enrolled Courses:</h3>
            <div class="courses-grid">
                @foreach($orderItems as $item)
                    @if($item->model === 'App\Models\Course')
                        @php $course = \App\Models\Course::find($item->item_id); @endphp
                        @if($course)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-icon">📚</div>
                                <h4 class="course-title">{{ $course->title }}</h4>
                            </div>
                            <div class="course-meta">
                                <strong>Instructor:</strong> {{ $course->user->full_name }} •
                                <strong>Level:</strong> {{ ucfirst($course->level ?? 'All Levels') }}
                            </div>
                            <p class="course-description">{{ Str::limit($course->description, 120) }}</p>
                            <p><strong>Duration:</strong> {{ $course->duration ?? 'Self-paced' }}</p>
                        </div>
                        @endif
                    @elseif($item->model === 'App\Models\Lesson')
                        @php $lesson = \App\Models\Lesson::find($item->item_id); @endphp
                        @if($lesson)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-icon">🎥</div>
                                <h4 class="course-title">{{ $lesson->title }}</h4>
                            </div>
                            <div class="course-meta">
                                <strong>From Course:</strong> {{ $lesson->course->title ?? 'N/A' }} •
                                <strong>Instructor:</strong> {{ $lesson->course->user->full_name ?? 'N/A' }}
                            </div>
                            <p class="course-description">{{ Str::limit($lesson->description ?? $lesson->content, 120) }}</p>
                        </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="access-info">
                <div class="access-title">🔑 How to Access Your Courses:</div>
                <p>Your courses are now available in your student dashboard. You can start learning immediately!</p>
            </div>

            <div class="quick-start">
                <h4>🚀 Quick Start Guide:</h4>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Log in to your Ed-Cademy account</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Go to "My Courses" in your dashboard</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Click on any course to start learning</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Complete lessons and earn certificates</div>
                </div>
            </div>

            <div class="resources">
                <h4 style="margin-top: 0; color: #333;">📚 Learning Resources:</h4>
                <div class="resource-grid">
                    <div class="resource-item">
                        <h5>Study Materials</h5>
                        <p>Downloadable resources and guides</p>
                    </div>
                    <div class="resource-item">
                        <h5>Discussion Forums</h5>
                        <p>Connect with other students</p>
                    </div>
                    <div class="resource-item">
                        <h5>Progress Tracking</h5>
                        <p>Monitor your learning journey</p>
                    </div>
                    <div class="resource-item">
                        <h5>Certificate</h5>
                        <p>Earn upon completion</p>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('student.my-courses') }}" class="cta-button">Start Learning Now</a>
            </div>

            <div class="support-section">
                <h4 style="margin-top: 0; color: #333;">Need Help Getting Started?</h4>
                <p>Our support team is here to help you make the most of your learning experience.</p>
                <p>
                    <a href="{{ route('web.contact.us') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">Contact Support</a> |
                    <a href="{{ route('web.faqs') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">View FAQ</a>
                </p>
            </div>

            <p>Happy learning! We're excited to see you grow and achieve your goals.</p>

            <p><strong>The Ed-Cademy Learning Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $student->email }} regarding your course enrollment.
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