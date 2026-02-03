<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Notification</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .notification-message { font-size: 18px; margin-bottom: 20px; color: #ffc107; font-weight: 600; }
        .review-card { background: linear-gradient(135deg, #fff8e1 0%, #fff3c4 100%); border: 2px solid #ffc107; border-radius: 12px; padding: 25px; margin: 20px 0; }
        .review-header { display: flex; align-items: center; margin-bottom: 20px; }
        .reviewer-avatar { width: 50px; height: 50px; background: #ffc107; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; font-weight: bold; margin-right: 15px; }
        .reviewer-info h3 { margin: 0; color: #333; font-size: 18px; }
        .reviewer-info p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        .rating-stars { margin: 15px 0; }
        .star { color: #ffc107; font-size: 18px; }
        .review-comment { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ffc107; }
        .review-comment p { margin: 0; font-style: italic; color: #555; line-height: 1.6; }
        .review-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; }
        .detail-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .detail-label { font-weight: 600; color: #666; }
        .detail-value { color: #333; text-align: right; }
        .response-section { background-color: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .response-title { font-weight: 600; color: #0056b3; margin-bottom: 10px; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .community-impact { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .impact-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 15px; }
        .impact-stat { background: white; padding: 15px; border-radius: 6px; border: 1px solid #e9ecef; }
        .impact-number { font-size: 24px; font-weight: bold; color: #ffc107; display: block; }
        .impact-label { font-size: 12px; color: #666; text-transform: uppercase; margin-top: 5px; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #ffc107; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>@if($isForRecipient)📝 New Review Received@else✅ Your Review Posted@endif</h1>
        </div>

        <div class="content">
            @if($isForRecipient)
                <div class="notification-message">
                    Hi @if($reviewType === 'course'){{ $review->course->user->full_name }}@else{{ $review->educator->full_name }}@endif!
                </div>

                <p>Great news! You've received a new {{ $reviewType }} review. Reviews help build trust and attract more students to your content.</p>
            @else
                <div class="notification-message">
                    Hi {{ $review->student->full_name }}!
                </div>

                <p>Your {{ $reviewType }} review has been successfully posted and is now visible to the community.</p>
            @endif

            <div class="review-card">
                <div class="review-header">
                    @if($isForRecipient)
                        <div class="reviewer-avatar">{{ substr($review->student->first_name, 0, 1) }}{{ substr($review->student->last_name, 0, 1) }}</div>
                        <div class="reviewer-info">
                            <h3>{{ $review->student->full_name }}</h3>
                            <p>Student Review</p>
                        </div>
                    @else
                        <div class="reviewer-avatar">YOU</div>
                        <div class="reviewer-info">
                            <h3>Your Review</h3>
                            <p>@if($reviewType === 'course')Course Review@elseEducator Review@endif</p>
                        </div>
                    @endif
                </div>

                <div class="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                    @endfor
                    <span style="margin-left: 10px; color: #666; font-size: 14px;">{{ $review->rating }}/5 stars</span>
                </div>

                @if($review->comment)
                <div class="review-comment">
                    <p>"{{ $review->comment }}"</p>
                </div>
                @endif

                <div class="review-details">
                    <div class="detail-row">
                        <span class="detail-label">Review Type:</span>
                        <span class="detail-value">{{ ucfirst($reviewType) }} Review</span>
                    </div>
                    @if($reviewType === 'course')
                        <div class="detail-row">
                            <span class="detail-label">Course:</span>
                            <span class="detail-value">{{ $review->course->title }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Category:</span>
                            <span class="detail-value">{{ $review->course->subject }}</span>
                        </div>
                    @else
                        <div class="detail-row">
                            <span class="detail-label">Educator:</span>
                            <span class="detail-value">{{ $review->educator->full_name }}</span>
                        </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Posted:</span>
                        <span class="detail-value">{{ $review->created_at->format('M j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>

            @if($isForRecipient)
            <div class="response-section">
                <div class="response-title">💬 Respond to This Review</div>
                <p>You can respond to reviews to thank students, clarify concerns, or provide additional context. Professional responses help build stronger relationships with your students.</p>
            </div>

            <div class="community-impact">
                <h4 style="margin-top: 0; color: #333;">📊 Community Impact</h4>
                <div class="impact-stats">
                    <div class="impact-stat">
                        <span class="impact-number">{{ $reviewType === 'course' ? $review->course->reviews->count() : $review->educator->reviews->count() }}</span>
                        <span class="impact-label">Total Reviews</span>
                    </div>
                    <div class="impact-stat">
                        <span class="impact-number">{{ $reviewType === 'course' ? number_format($review->course->reviews->avg('rating'), 1) : number_format($review->educator->reviews->avg('rating'), 1) }}</span>
                        <span class="impact-label">Avg Rating</span>
                    </div>
                    <div class="impact-stat">
                        <span class="impact-number">{{ $review->rating >= 4 ? 'Positive' : ($review->rating >= 3 ? 'Neutral' : 'Needs Work') }}</span>
                        <span class="impact-label">This Review</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="@if($reviewType === 'course'){{ route('educator.reviews.index') }}@else{{ route('educator.reviews.index') }}@endif" class="cta-button">
                    @if($isForRecipient)View All Reviews@elseView Your Reviews@endif
                </a>
            </div>
            @endif

            @if($isForRecipient)
            <p><strong>💡 Review Best Practices:</strong></p>
            <ul>
                <li>Thank students for their feedback</li>
                <li>Address any concerns professionally</li>
                <li>Use reviews to improve your content</li>
                <li>Highlight positive aspects in your responses</li>
            </ul>

            <p>Reviews are crucial for building credibility and helping students make informed decisions. Keep up the great work!</p>
            @else
            <p><strong>🎯 Your Review Matters:</strong></p>
            <ul>
                <li>Help other students make informed decisions</li>
                <li>Support quality educators and content</li>
                <li>Contribute to the learning community</li>
                <li>Your feedback helps improve the platform</li>
            </ul>

            <p>Thank you for taking the time to share your experience. Your feedback helps make Ed-Cademy better for everyone!</p>
            @endif

            <p><strong>The Ed-Cademy Community Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent regarding a {{ $reviewType }} review on Ed-Cademy.
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