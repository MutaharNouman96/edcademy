<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Certificate</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px 30px; text-align: center; position: relative; overflow: hidden; }
        .header::before { content: "🎓"; font-size: 60px; position: absolute; top: 10px; right: 20px; opacity: 0.3; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .congratulations { font-size: 24px; margin-bottom: 20px; color: #28a745; font-weight: 700; text-align: center; }
        .achievement-message { font-size: 18px; margin-bottom: 30px; text-align: center; color: #666; }
        .certificate-preview { background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%); border: 3px solid #28a745; border-radius: 15px; padding: 30px; margin: 30px 0; text-align: center; position: relative; }
        .certificate-header { font-size: 14px; color: #666; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .certificate-title { font-size: 22px; font-weight: bold; color: #28a745; margin: 10px 0; }
        .certificate-subtitle { font-size: 16px; color: #666; margin: 10px 0; }
        .certificate-name { font-size: 24px; font-weight: bold; color: #333; margin: 20px 0; text-transform: uppercase; }
        .certificate-details { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 25px 0; }
        .certificate-detail { background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef; }
        .detail-label { font-size: 12px; color: #666; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
        .detail-value { font-size: 14px; color: #333; font-weight: 500; }
        .certificate-seal { margin-top: 20px; opacity: 0.8; }
        .course-stats { background-color: #f8f9fa; padding: 25px; border-radius: 8px; margin: 30px 0; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
        .stat-item { text-align: center; }
        .stat-number { font-size: 28px; font-weight: bold; color: #28a745; display: block; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; margin-top: 5px; }
        .next-steps { background-color: #f0f8ff; padding: 25px; border-radius: 8px; margin: 30px 0; border-left: 4px solid #007bff; }
        .steps-title { font-weight: 600; color: #007bff; margin-bottom: 15px; }
        .step { margin-bottom: 10px; display: flex; align-items: flex-start; }
        .step-number { background: #007bff; color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .share-section { background-color: #fff5f5; padding: 25px; border-radius: 8px; margin: 30px 0; border-left: 4px solid #e83e8c; }
        .share-title { font-weight: 600; color: #e83e8c; margin-bottom: 15px; }
        .social-buttons { display: flex; gap: 10px; justify-content: center; margin-top: 15px; }
        .social-button { display: inline-block; padding: 10px 20px; background: #e83e8c; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #28a745; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Certificate of Completion</h1>
        </div>

        <div class="content">
            <div class="congratulations">
                🎉 Congratulations!
            </div>

            <div class="achievement-message">
                You've successfully completed the course and earned your certificate!
            </div>

            <div class="certificate-preview">
                <div class="certificate-header">Ed-Cademy Certificate of Completion</div>
                <div class="certificate-title">Certificate</div>
                <div class="certificate-subtitle">This certifies that</div>
                <div class="certificate-name">{{ $student->full_name }}</div>
                <div class="certificate-subtitle">has successfully completed</div>
                <div style="font-size: 18px; font-weight: bold; color: #333; margin: 15px 0;">{{ $course->title }}</div>

                <div class="certificate-details">
                    <div class="certificate-detail">
                        <div class="detail-label">Course Instructor</div>
                        <div class="detail-value">{{ $educator->full_name }}</div>
                    </div>
                    <div class="certificate-detail">
                        <div class="detail-label">Completion Date</div>
                        <div class="detail-value">{{ $completionDate->format('F j, Y') }}</div>
                    </div>
                    <div class="certificate-detail">
                        <div class="detail-label">Course Level</div>
                        <div class="detail-value">{{ ucfirst($course->level ?? 'All Levels') }}</div>
                    </div>
                    <div class="certificate-detail">
                        <div class="detail-label">Certificate ID</div>
                        <div class="detail-value">{{ $certificateData['certificate_id'] ?? 'CERT-' . $student->id . '-' . $course->id }}</div>
                    </div>
                </div>

                <div class="certificate-seal">
                    🏆
                </div>
            </div>

            <div class="course-stats">
                <h4 style="margin-top: 0; color: #333; text-align: center;">Your Learning Achievement</h4>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">{{ $course->lessons->count() }}</span>
                        <span class="stat-label">Lessons Completed</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $course->duration ?? 'N/A' }}</span>
                        <span class="stat-label">Course Duration</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $course->reviews->avg('rating') ? number_format($course->reviews->avg('rating'), 1) : 'N/A' }}</span>
                        <span class="stat-label">Course Rating</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ $certificateUrl }}" class="cta-button">Download Certificate</a>
            </div>

            <div class="next-steps">
                <div class="steps-title">What's Next in Your Learning Journey?</div>
                <div class="step">
                    <div class="step-number">1</div>
                    <div>Explore related courses from {{ $educator->full_name }}</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div>Check out advanced courses in {{ $course->subject }}</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div>Share your achievement on LinkedIn or social media</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div>Consider becoming an educator yourself</div>
                </div>
            </div>

            <div class="share-section">
                <div class="share-title">🎊 Share Your Achievement</div>
                <p>Congratulations on completing this course! Share your certificate with your network to showcase your new skills.</p>
                <div class="social-buttons">
                    <a href="#" class="social-button">📘 LinkedIn</a>
                    <a href="#" class="social-button">🐦 Twitter</a>
                    <a href="#" class="social-button">📷 Instagram</a>
                </div>
            </div>

            <p><strong>🏆 Certificate Benefits:</strong></p>
            <ul>
                <li>Official recognition of your course completion</li>
                <li>Shareable proof of your new skills</li>
                <li>Adds credibility to your professional profile</li>
                <li>Can be used for career advancement or certifications</li>
                <li>Permanent record in your Ed-Cademy profile</li>
            </ul>

            <p>Thank you for choosing Ed-Cademy for your learning journey. We wish you continued success in all your future endeavors!</p>

            <p><strong>The Ed-Cademy Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This certificate email was sent to {{ $student->email }} for completing "{{ $course->title }}".
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